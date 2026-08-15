<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    /* dompdf has limited CSS support and no reliable access to remote
       Google Fonts without extra config — using its built-in font set
       (DejaVu Sans, Georgia, Courier) rather than the app's Fraunces/
       Inter/IBM Plex Mono, which are only loaded in the browser layouts. */
    body { font-family: "DejaVu Sans", sans-serif; font-size: 10px; color: #16233F; margin: 24px; }
    h1 { font-family: Georgia, serif; font-size: 18px; margin: 0 0 2px; }
    .meta { font-size: 9px; color: #666666; margin-bottom: 18px; }
    table { width: 100%; border-collapse: collapse; }
    th {
        text-align: left; font-size: 8px; text-transform: uppercase; letter-spacing: 0.05em;
        color: #666666; border-bottom: 1.5px solid #16233F; padding: 6px 8px;
    }
    td { padding: 6px 8px; border-bottom: 0.5px solid #dddddd; font-size: 9.5px; vertical-align: top; }
    .ref { font-family: Courier, monospace; }
    tr:nth-child(even) td { background: #F6F4EE; }
    .empty { text-align: center; color: #999999; padding: 20px; }
</style>
</head>
<body>
    <h1>{{ $title }}</h1>
    <div class="meta">
        {{ $companyName ?? 'GDPR Compliance Register' }} &middot; Generated {{ $generatedAt->format('d M Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $i => $cell)
                        <td class="{{ $i === 0 ? 'ref' : '' }}">{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td class="empty" colspan="{{ count($headers) }}">No records.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
