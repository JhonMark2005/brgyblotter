<?php

namespace App\Services;

/**
 * BrevoMailer — Brevo Transactional Email Service
 * Uses Brevo REST API via cURL (no additional packages required)
 */
class BrevoMailer
{
    // ----------------------------------------------------------
    // Core API send
    // ----------------------------------------------------------
    private static function send(array $to, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        if (!config('mail.brevo.enabled', false)) return false;

        $apiKey = config('mail.brevo.api_key', '');
        if (empty($apiKey) || $apiKey === 'your-brevo-api-key-here') return false;

        $payload = [
            'sender'      => ['name' => config('mail.brevo.from_name'), 'email' => config('mail.brevo.from_email')],
            'to'          => $to,
            'subject'     => $subject,
            'htmlContent' => $htmlBody,
            'textContent' => $textBody ?: strip_tags($htmlBody),
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'api-key: ' . $apiKey,
                'content-type: application/json',
            ],
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $httpCode >= 200 && $httpCode < 300;
    }

    // ----------------------------------------------------------
    // Format a case number nicely
    // ----------------------------------------------------------
    private static function caseNo(array $incident): string
    {
        return '#' . str_pad($incident['id'], 4, '0', STR_PAD_LEFT);
    }

    // ----------------------------------------------------------
    // Shared email wrapper (header + footer)
    // ----------------------------------------------------------
    private static function template(string $title, string $body): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#f3f4f6;padding:32px 16px;">
    <tr><td align="center">
      <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
        <tr>
          <td style="background:linear-gradient(135deg,#166534,#16a34a);padding:28px 32px;text-align:center;">
            <p style="margin:0 0 4px;color:#bbf7d0;font-size:12px;letter-spacing:1px;text-transform:uppercase;">Barangay Caranas, Motiong, Samar</p>
            <h1 style="margin:0;color:#ffffff;font-size:20px;font-weight:bold;">Digital Barangay Blotter</h1>
          </td>
        </tr>
        <tr>
          <td style="background:#f0fdf4;border-bottom:2px solid #bbf7d0;padding:16px 32px;">
            <h2 style="margin:0;color:#166534;font-size:16px;">{$title}</h2>
          </td>
        </tr>
        <tr>
          <td style="padding:28px 32px;color:#374151;font-size:14px;line-height:1.7;">
            {$body}
          </td>
        </tr>
        <tr>
          <td style="background:#f9fafb;border-top:1px solid #e5e7eb;padding:16px 32px;text-align:center;">
            <p style="margin:0;color:#9ca3af;font-size:11px;">This is an automated notification from the Digital Barangay Blotter System.<br>Barangay Caranas, Municipality of Motiong, Samar.</p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;
    }

    // ----------------------------------------------------------
    // Field row helper for case detail tables in emails
    // ----------------------------------------------------------
    private static function fieldRow(string $label, string $value): string
    {
        return "<tr>
          <td style='padding:6px 12px;background:#f9fafb;border:1px solid #e5e7eb;font-size:12px;font-weight:bold;color:#6b7280;width:35%;text-transform:uppercase;letter-spacing:0.5px;'>{$label}</td>
          <td style='padding:6px 12px;border:1px solid #e5e7eb;font-size:13px;color:#111827;'>{$value}</td>
        </tr>";
    }

    // ----------------------------------------------------------
    // Case detail table block
    // ----------------------------------------------------------
    private static function caseTable(array $i): string
    {
        $rows  = self::fieldRow('Case Number',   self::caseNo($i));
        $rows .= self::fieldRow('Complainant',   htmlspecialchars($i['complainant_name']));
        $rows .= self::fieldRow('Respondent',    htmlspecialchars($i['respondent_name']));
        $rows .= self::fieldRow('Incident Type', htmlspecialchars($i['incident_type']));
        $rows .= self::fieldRow('Date',          date('F j, Y', strtotime($i['date'])));
        $rows .= self::fieldRow('Location',      htmlspecialchars($i['location']));
        $rows .= self::fieldRow('Status',        strtoupper(str_replace('_', ' ', $i['status'])));
        return "<table width='100%' cellpadding='0' cellspacing='0' style='border-collapse:collapse;margin:16px 0;'>{$rows}</table>";
    }

    // ----------------------------------------------------------
    // 1. Notify admin — new case filed
    // ----------------------------------------------------------
    public static function notifyAdminNewCase(array $incident, array $admins = []): bool
    {
        $caseNo = self::caseNo($incident);
        $body   = "
            <p>A new blotter incident has been filed and requires your attention.</p>
            " . self::caseTable($incident) . "
            <p style='margin-top:8px;'><strong>Description:</strong><br>
            <span style='color:#4b5563;'>" . nl2br(htmlspecialchars($incident['description'])) . "</span></p>
            <p style='color:#6b7280;font-size:12px;margin-top:16px;'>Recorded by: <strong>" . htmlspecialchars($incident['recorded_by_name'] ?? 'Staff') . "</strong> on " . date('F j, Y g:i A') . "</p>
        ";

        $recipients = [];
        foreach ($admins as $admin) {
            if (!empty($admin['email'])) {
                $recipients[] = ['email' => $admin['email'], 'name' => $admin['full_name']];
            }
        }
        if (empty($recipients) && config('mail.brevo.admin_email')) {
            $recipients[] = ['email' => config('mail.brevo.admin_email'), 'name' => config('mail.brevo.admin_name')];
        }
        if (empty($recipients)) return false;

        return self::send(
            $recipients,
            "New Incident Filed — {$caseNo}: {$incident['incident_type']}",
            self::template("New Incident Filed: {$caseNo}", $body)
        );
    }

    // ----------------------------------------------------------
    // 2. Notify parties — hearing scheduled
    // ----------------------------------------------------------
    public static function notifyHearingSchedule(array $incident): bool
    {
        if (empty($incident['hearing_date'])) return false;

        $caseNo      = self::caseNo($incident);
        $hearingDate = date('l, F j, Y \a\t g:i A', strtotime($incident['hearing_date']));
        $notes       = !empty($incident['hearing_notes'])
            ? "<p><strong>Additional Notes:</strong><br><span style='color:#4b5563;'>" . nl2br(htmlspecialchars($incident['hearing_notes'])) . "</span></p>"
            : '';

        $body = "
            <p>A hearing has been scheduled for your blotter case. Please be present on the date and time indicated below.</p>
            <div style='background:#f0fdf4;border-left:4px solid #16a34a;padding:14px 18px;margin:16px 0;border-radius:4px;'>
                <p style='margin:0 0 4px;font-size:12px;color:#166534;font-weight:bold;text-transform:uppercase;letter-spacing:0.5px;'>Hearing Schedule</p>
                <p style='margin:0;font-size:18px;font-weight:bold;color:#14532d;'>{$hearingDate}</p>
            </div>
            " . self::caseTable($incident) . "
            {$notes}
            <p style='color:#6b7280;font-size:12px;margin-top:16px;'>Please bring a valid ID, any supporting documents, and evidence relevant to the case. Arrive 15 minutes before the scheduled time.</p>
        ";

        $html = self::template("Hearing Scheduled — {$caseNo}", $body);
        $sent = false;

        if (!empty($incident['complainant_email'])) {
            $sent = self::send(
                [['email' => $incident['complainant_email'], 'name' => $incident['complainant_name']]],
                "Hearing Schedule Notice — Case {$caseNo}",
                $html
            );
        }
        if (!empty($incident['respondent_email'])) {
            $sent = self::send(
                [['email' => $incident['respondent_email'], 'name' => $incident['respondent_name']]],
                "Hearing Schedule Notice — Case {$caseNo}",
                $html
            ) || $sent;
        }

        return $sent;
    }

    // ----------------------------------------------------------
    // 3. Password reset link
    // ----------------------------------------------------------
    public static function sendPasswordReset(string $toEmail, string $toName, string $resetUrl): bool
    {
        $body = "
            <p>Hello <strong>" . htmlspecialchars($toName) . "</strong>,</p>
            <p>We received a request to reset your password for the Digital Barangay Blotter System. Click the button below to set a new password.</p>
            <div style='text-align:center;margin:28px 0;'>
                <a href='{$resetUrl}' style='background:#166534;color:#ffffff;padding:12px 28px;border-radius:8px;text-decoration:none;font-weight:bold;font-size:14px;display:inline-block;'>
                    Reset My Password
                </a>
            </div>
            <p style='color:#6b7280;font-size:12px;'>This link will expire in <strong>1 hour</strong>. If you did not request a password reset, you can safely ignore this email.</p>
            <p style='color:#6b7280;font-size:12px;'>Or copy and paste this URL into your browser:<br><span style='color:#166534;word-break:break-all;'>{$resetUrl}</span></p>
        ";

        return self::send(
            [['email' => $toEmail, 'name' => $toName]],
            'Password Reset — Digital Barangay Blotter',
            self::template('Password Reset Request', $body)
        );
    }

    // ----------------------------------------------------------
    // 4. Password reset confirmation
    // ----------------------------------------------------------
    public static function sendPasswordResetConfirmation(string $toEmail, string $toName): bool
    {
        $body = "
            <p>Hello <strong>" . htmlspecialchars($toName) . "</strong>,</p>
            <p>Your password for the Digital Barangay Blotter System has been <strong>successfully reset</strong>.</p>
            <div style='background:#f0fdf4;border-left:4px solid #16a34a;padding:14px 18px;margin:16px 0;border-radius:4px;'>
                <p style='margin:0;font-size:13px;color:#166534;'>You can now log in with your new password.</p>
            </div>
            <p style='color:#6b7280;font-size:12px;margin-top:16px;'>If you did not make this change, please contact the Barangay Hall immediately as your account may be compromised.</p>
            <p style='color:#6b7280;font-size:12px;'>Time of reset: <strong>" . date('F j, Y g:i A') . "</strong></p>
        ";

        return self::send(
            [['email' => $toEmail, 'name' => $toName]],
            'Password Successfully Reset — Digital Barangay Blotter',
            self::template('Password Reset Successful', $body)
        );
    }

    // ----------------------------------------------------------
    // 5. Notify complainant — status updated
    // ----------------------------------------------------------
    public static function notifyStatusUpdate(array $incident, string $oldStatus): bool
    {
        if (empty($incident['complainant_email'])) return false;

        $caseNo    = self::caseNo($incident);
        $newStatus = strtoupper(str_replace('_', ' ', $incident['status']));
        $oldLabel  = strtoupper(str_replace('_', ' ', $oldStatus));

        $statusMessages = [
            'under_investigation' => 'Your case is now <strong>under investigation</strong>. The barangay is actively reviewing the incident.',
            'resolved'            => 'Your case has been <strong>resolved</strong>. The barangay has concluded its review of this incident.',
            'dismissed'           => 'Your case has been <strong>dismissed</strong>. Please contact the Barangay Hall if you have questions.',
            'pending'             => 'Your case status has been updated back to <strong>pending</strong>.',
        ];

        $message = $statusMessages[$incident['status']] ?? "Your case status has been updated to <strong>{$newStatus}</strong>.";

        $body = "
            <p>The status of your blotter case has been updated.</p>
            <div style='background:#f0fdf4;border-left:4px solid #16a34a;padding:14px 18px;margin:16px 0;border-radius:4px;'>
                <p style='margin:0 0 6px;font-size:12px;color:#166534;font-weight:bold;text-transform:uppercase;letter-spacing:0.5px;'>Status Update</p>
                <p style='margin:0 0 4px;'><span style='color:#6b7280;font-size:12px;'>From:</span> <strong style='color:#374151;'>{$oldLabel}</strong></p>
                <p style='margin:0;'><span style='color:#6b7280;font-size:12px;'>To:</span> <strong style='color:#166534;font-size:15px;'>{$newStatus}</strong></p>
            </div>
            <p>{$message}</p>
            " . self::caseTable($incident) . "
            <p style='color:#6b7280;font-size:12px;margin-top:16px;'>For more information, visit the Barangay Hall of Caranas, Motiong, Samar.</p>
        ";

        return self::send(
            [['email' => $incident['complainant_email'], 'name' => $incident['complainant_name']]],
            "Case Status Update — {$caseNo} is now {$newStatus}",
            self::template("Case Status Updated: {$caseNo}", $body)
        );
    }
}
