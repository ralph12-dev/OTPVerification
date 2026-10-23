<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'OTP Verification')</title>
    <style>
        :root {
            --ink: #0f172a;
            --muted: #64748b;
            --line: #dbe2ea;
            --brand: #4338ca;
            --brand-dark: #3730a3;
            --ok: #047857;
            --ok-bg: #ecfdf5;
            --err: #b91c1c;
            --err-bg: #fef2f2;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 2rem 1rem;
            font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at 15% 10%, #e0e7ff 0, transparent 45%),
                radial-gradient(circle at 90% 85%, #dbeafe 0, transparent 40%),
                #f8fafc;
            line-height: 1.5;
        }

        .shell { width: 100%; max-width: 430px; }

        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .55rem;
            margin-bottom: 1.25rem;
            font-weight: 700;
            color: #312e81;
        }

        .brand-mark {
            width: 2rem;
            height: 2rem;
            display: grid;
            place-items: center;
            border-radius: 9px;
            background: linear-gradient(135deg, #1F2937, #4B5563);
            color: #fff;
            font-size: .65rem;
            letter-spacing: .04em;
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: clamp(1.5rem, 5vw, 2.5rem);
            box-shadow: 0 20px 50px -20px rgba(15, 23, 42, .25);
        }

        h1 {
            margin: 0 0 .5rem;
            font-size: clamp(1.4rem, 4vw, 1.75rem);
            letter-spacing: -.02em;
        }

        .muted {
            margin: 0 0 1.5rem;
            color: var(--muted);
            font-size: .95rem;
        }

        .field { margin-bottom: 1.1rem; }

        label {
            display: block;
            margin-bottom: .4rem;
            font-size: .875rem;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: .8rem .95rem;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background: #fff;
            color: inherit;
            font: inherit;
            transition: border-color .15s, box-shadow .15s;
        }

        input:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(67, 56, 202, .18);
        }

        input[aria-invalid="true"] { border-color: var(--err); }

        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            letter-spacing: .45em;
            padding-left: calc(.95rem + .45em);
            font-variant-numeric: tabular-nums;
        }

        button {
            width: 100%;
            padding: .85rem 1rem;
            border: 0;
            border-radius: 10px;
            background: var(--brand);
            color: #fff;
            font: inherit;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .05s;
        }

        button:hover { background: var(--brand-dark); }
        button:active { transform: translateY(1px); }

        button:focus-visible {
            outline: 3px solid rgba(67, 56, 202, .35);
            outline-offset: 2px;
        }

        .alert {
            margin: 0 0 1.25rem;
            padding: .75rem .9rem;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: .9rem;
        }

        .alert-success { color: var(--ok); background: var(--ok-bg); border-color: #a7f3d0; }
        .alert-error { color: var(--err); background: var(--err-bg); border-color: #fecaca; }

        .field-error {
            margin: .4rem 0 0;
            color: var(--err);
            font-size: .85rem;
        }

        .small { margin: 1.25rem 0 0; font-size: .875rem; }

        a { color: var(--brand); font-weight: 600; text-decoration: none; }
        a:hover { text-decoration: underline; }

        .email { overflow-wrap: anywhere; }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            margin-bottom: .9rem;
            padding: .3rem .7rem;
            border-radius: 999px;
            background: var(--ok-bg);
            color: var(--ok);
            font-size: .8rem;
            font-weight: 700;
        }

        .badge::before {
            content: "";
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            background: currentColor;
        }
    </style>
</head>
<body>
    <main class="shell">
        <div class="brand">
            <span class="brand-mark" aria-hidden="true">OTP</span>
            <span>Secure Access</span>
        </div>

        <div class="card">
            @yield('content')
        </div>
    </main>
</body>
</html>
