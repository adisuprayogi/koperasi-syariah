<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Anggota - {{ $anggota->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
            padding: 10mm;
        }

        body {
            font-family: '{{ $settings->font_family ?? 'Arial' }}', sans-serif;
            margin: 0;
            padding: 0;
            background: #f0f0f0;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20mm;
            padding: 10mm;
        }

        .card-wrapper {
            position: relative;
            width: 85.6mm;
            height: 53.98mm;
            page-break-inside: avoid;
        }

        .card {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .card-front {
            background: {{ getCardBackground($settings, 'front') }};
        }

        .card-back {
            background: {{ getCardBackground($settings, 'back') }};
        }

        .card-element {
            position: absolute;
            font-size: 12px;
        }

        .logo {
            border-radius: 4px;
        }

        .foto-anggota {
            border-radius: 50%;
            border: 2px solid white;
        }

        .barcode {
            background: white;
            padding: 2px;
            border-radius: 4px;
        }

        .tanda-tangan {
            max-width: 100%;
            max-height: 100%;
        }

        .text-white {
            color: {{ $settings->text_color_front ?? '#ffffff' }};
        }

        .text-dark {
            color: {{ $settings->text_color_back ?? '#333333' }};
        }

        @media print {
            body {
                background: white;
            }

            .card-container {
                padding: 0;
            }

            .card {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <div class="card-container">
        <!-- Multiple cards for printing -->
        @for($i = 0; $i < 6; $i++)
        <div class="card-wrapper">
            <!-- Front Card -->
            <div class="card card-front">
                @include('admin.kartu.parts.card-front', ['preview' => false])
            </div>

            <!-- Back Card (positioned below front for cutting) -->
            <div class="card card-back" style="top: 57mm;">
                @include('admin.kartu.parts.card-back', ['preview' => false])
            </div>
        </div>
        @endfor
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(() => {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>