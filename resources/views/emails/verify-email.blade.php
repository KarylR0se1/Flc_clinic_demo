<!doctype html>
<html>
  <body style="font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
    <p>Hello,</p>

    <p>Thank you for registering. Please verify your email by clicking the button below. The link expires in 60 minutes.</p>

    <p>
      <a href="{{ $url }}" style="display:inline-block;padding:10px 16px;background:#2563eb;color:#fff;border-radius:6px;text-decoration:none;">
        Verify my email
      </a>
    </p>

    <p>If the button doesn't work, copy & paste the URL below into your browser:</p>
    <p><small>{{ $url }}</small></p>

    <p>If you did not register, you can ignore this message.</p>
  </body>
</html>
