<?php

namespace Tests\Feature;

use App\Mail\SendOTPEmail;
use App\Models\OtpCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_page_renders(): void
    {
        $this->get(route('otp.request'))->assertOk()->assertSee('Email Verification');
    }

    public function test_protected_home_requires_verification(): void
    {
        $this->get(route('home'))->assertRedirect(route('otp.request'));
    }

    public function test_verify_page_renders_for_the_requested_email(): void
    {
        $this->withSession(['otp_email' => 'user@example.com'])
            ->get(route('otp.verify'))
            ->assertOk()
            ->assertSee('OTP Verification')
            ->assertSee('user@example.com')
            ->assertSee('Verify OTP');
    }

    public function test_sending_otp_emails_a_six_digit_code_and_stores_it_hashed(): void
    {
        Mail::fake();

        $this->post(route('otp.send'), ['email' => 'user@example.com'])
            ->assertRedirect(route('otp.verify'));

        $mailable = null;
        Mail::assertSent(SendOTPEmail::class, function (SendOTPEmail $mail) use (&$mailable) {
            $mailable = $mail;

            return true;
        });

        $this->assertMatchesRegularExpression('/^\d{6}$/', $mailable->otp);
        $this->assertStringContainsString($mailable->otp, $mailable->render());

        $record = OtpCode::where('email', 'user@example.com')->firstOrFail();
        $this->assertNotSame($mailable->otp, $record->code);
        $this->assertTrue(Hash::check($mailable->otp, $record->code));
        $this->assertTrue($record->expires_at->isFuture());
    }

    public function test_empty_otp_shows_required_message(): void
    {
        $this->withSession(['otp_email' => 'user@example.com'])
            ->post(route('otp.verify.submit'), ['otp' => ''])
            ->assertSessionHasErrors(['otp' => 'Please enter the OTP.']);
    }

    public function test_wrong_length_otp_shows_length_message(): void
    {
        $this->withSession(['otp_email' => 'user@example.com'])
            ->post(route('otp.verify.submit'), ['otp' => '12345'])
            ->assertSessionHasErrors(['otp' => 'The OTP must contain exactly six digits.']);
    }

    public function test_correct_otp_verifies_and_cannot_be_reused(): void
    {
        Mail::fake();

        $this->post(route('otp.send'), ['email' => 'user@example.com']);

        $otp = null;
        Mail::assertSent(SendOTPEmail::class, function (SendOTPEmail $mail) use (&$otp) {
            $otp = $mail->otp;

            return true;
        });

        $this->post(route('otp.verify.submit'), ['otp' => $otp])
            ->assertRedirect(route('home'))
            ->assertSessionHas('success', 'OTP verified successfully!');

        $this->assertDatabaseCount('otp_codes', 0);
        $this->get(route('home'))->assertOk()->assertSee('OTP verified successfully!');

        $this->post(route('otp.verify.submit'), ['otp' => $otp])
            ->assertRedirect(route('otp.request'));
    }

    public function test_expired_otp_is_rejected(): void
    {
        OtpCode::create([
            'email' => 'user@example.com',
            'code' => Hash::make('123456'),
            'expires_at' => now()->subMinute(),
            'attempts' => 0,
        ]);

        $this->withSession(['otp_email' => 'user@example.com'])
            ->post(route('otp.verify.submit'), ['otp' => '123456'])
            ->assertSessionHas('error', 'The OTP is incorrect or has expired.');

        $this->assertDatabaseCount('otp_codes', 0);
    }

    public function test_otp_is_locked_after_five_failed_attempts(): void
    {
        OtpCode::create([
            'email' => 'user@example.com',
            'code' => Hash::make('123456'),
            'expires_at' => now()->addMinutes(5),
            'attempts' => 0,
        ]);

        $session = ['otp_email' => 'user@example.com'];

        for ($i = 0; $i < 5; $i++) {
            $this->withSession($session)
                ->post(route('otp.verify.submit'), ['otp' => '000000'])
                ->assertSessionHas('error', 'The OTP is incorrect or has expired.');
        }

        $this->assertDatabaseCount('otp_codes', 0);
    }
}
