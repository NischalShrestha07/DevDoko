<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: -apple-system, Segoe UI, Roboto, sans-serif; background: #f0f2f5; padding: 24px; margin: 0;">
    <table role="presentation" width="100%" style="max-width: 480px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="padding: 32px;">
                <h2 style="margin: 0 0 16px; color: #1c1e21;">You've been invited to join {{ $group->name }}</h2>
                <p style="color: #65676b; line-height: 1.5;">
                    {{ $inviter->name }} invited you to join <strong>{{ $group->name }}</strong> on DevDoko.
                </p>
                @if($invitation->message)
                <p style="color: #65676b; line-height: 1.5; background: #f0f2f5; padding: 12px 16px; border-radius: 8px;">
                    "{{ $invitation->message }}"
                </p>
                @endif
                <p style="margin: 24px 0;">
                    <a href="{{ $acceptUrl }}" style="background: #667eea; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; display: inline-block;">
                        View Invitation
                    </a>
                </p>
                <p style="color: #8b949e; font-size: 13px;">
                    This invitation expires on {{ $invitation->expires_at->format('M j, Y') }}.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
