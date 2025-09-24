<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JCR #{{ $jcr->id }} - Printable</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .checklist-container {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .checklist-header {
            background-color: #f5f5f5;
            padding: 10px;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        .checklist-body {
            border: 1px solid #ddd;
            border-top: none;
            padding: 15px;
        }
        .signature-section {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #333;
        }
        .signature-box {
            display: inline-block;
            width: 200px;
            margin-right: 50px;
            margin-top: 50px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 100%;
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .no-print {
            display: none;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
    <div class="no-print text-center mb-3">
        <button onclick="window.print()" class="btn btn-primary">Print JCR</button>
        <a href="{{ route('jcr.show', $jcr->id) }}" class="btn btn-secondary">Back to JCR</a>
    </div>

    <div class="container">
        @include('jcr._preview_content')
    </div>

    <!-- Checklist A -->
    @if($groupedChecklists['a'])
    <div class="checklist-container">
        <div class="checklist-header">
            Checklist A: Pre-Departure Checklist
        </div>
        <div class="checklist-body">
            <p><strong>Job Type:</strong> {{ $groupedChecklists['a']->job_type }}</p>
            <p><strong>Created By:</strong> {{ $groupedChecklists['a']->creator->name }}</p>
            
            <table class="mt-3">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedChecklists['a']->checklist_data as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['status'] ? 'Completed' : 'Not Completed' }}</td>
                        <td>{{ $item['comments'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="signature-section">
                <h5>Signatures</h5>
                @foreach($groupedChecklists['a']->signatures as $signature)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>{{ $signature->user->name }}</p>
                    <p>{{ $signature->signed_at->format('Y-m-d H:i') }}</p>
                    @if($signature->comments)
                    <p><small>Comments: {{ $signature->comments }}</small></p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Checklist B -->
    @if($groupedChecklists['b'])
    <div class="checklist-container">
        <div class="checklist-header">
            Checklist B: On-Site Checklist
        </div>
        <div class="checklist-body">
            <p><strong>Job Type:</strong> {{ $groupedChecklists['b']->job_type }}</p>
            <p><strong>Created By:</strong> {{ $groupedChecklists['b']->creator->name }}</p>
            
            <table class="mt-3">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedChecklists['b']->checklist_data as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['status'] ? 'Completed' : 'Not Completed' }}</td>
                        <td>{{ $item['comments'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="signature-section">
                <h5>Signatures</h5>
                @foreach($groupedChecklists['b']->signatures as $signature)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>{{ $signature->user->name }}</p>
                    <p>{{ $signature->signed_at->format('Y-m-d H:i') }}</p>
                    @if($signature->comments)
                    <p><small>Comments: {{ $signature->comments }}</small></p>
                    @endif
                </div>
                @endforeach
                
                @if($groupedChecklists['b']->externalSignature)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p><strong>Rig Incharge:</strong> {{ $groupedChecklists['b']->externalSignature->name }}</p>
                    <p><strong>Designation:</strong> {{ $groupedChecklists['b']->externalSignature->designation }}</p>
                    <p><strong>CPF No:</strong> {{ $groupedChecklists['b']->externalSignature->cpf_no }}</p>
                    <p>{{ $groupedChecklists['b']->externalSignature->signed_at->format('Y-m-d H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Checklist C -->
    @if($groupedChecklists['c'])
    <div class="checklist-container">
        <div class="checklist-header">
            Checklist C: Upon-Arrival Checklist
        </div>
        <div class="checklist-body">
            <p><strong>Job Type:</strong> {{ $groupedChecklists['c']->job_type }}</p>
            <p><strong>Created By:</strong> {{ $groupedChecklists['c']->creator->name }}</p>
            
            <table class="mt-3">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Status</th>
                        <th>Comments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedChecklists['c']->checklist_data as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['status'] ? 'Completed' : 'Not Completed' }}</td>
                        <td>{{ $item['comments'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="signature-section">
                <h5>Signatures</h5>
                @foreach($groupedChecklists['c']->signatures as $signature)
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>{{ $signature->user->name }}</p>
                    <p>{{ $signature->signed_at->format('Y-m-d H:i') }}</p>
                    @if($signature->comments)
                    <p><small>Comments: {{ $signature->comments }}</small></p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="footer no-print mt-4 text-center">
        <p>Printed on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <script>
        window.onload = function() {
            // Auto-print if desired
            // window.print();
        }
    </script>
</body>
</html>