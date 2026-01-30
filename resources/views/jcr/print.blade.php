<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JCR #{{ $jcr->id }} - Printable</title>
    <!-- Bootstrap CSS -->
    <link href="/static/bootstrap-5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="/static/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
    /* Default: Portrait */
    @page {
        size: A4 portrait;
        margin: 15mm;
    }

    /* Landscape page for JCR */
    @page jcr-landscape {
        size: A4 landscape;
        margin: 10mm;
    }

    /* Assign named page */
    .page-portrait {
        page: portrait;
    }



    @media print {
        .page-break {
            page-break-after: always;
        }
        /* Reduce font size for landscape (JCR) page only */
        .page-landscape {
            font-size: 9px;   /* default is usually ~14px */
        }

        .page-landscape h1 { font-size: 16px; }
        .page-landscape h2 { font-size: 15px; }
        .page-landscape h3 { font-size: 14px; }
        .page-landscape h4 { font-size: 13px; }

        .page-landscape table th,
        .page-landscape table td {
            font-size: 9px;
            padding: 2px 4px;
        }

        .page-landscape .form-control,
        .page-landscape input,
        .page-landscape textarea {
            font-size: 9px;
        }
    }
</style>

</head>
<body>
    <div class="text-center mb-3">
        <button onclick="window.print()" class="btn btn-primary">Print JCR</button>
        <a href="{{ route('jcr.show', $jcr->id) }}" class="btn btn-secondary">Back to JCR</a>
    </div>

    <!-- Time Register -->
    @if($jcr->timeRegister)
    <div class="row mb-4">
        <div class="col-md-11 mx-auto">
            <div class="card mb-3 w-100 mx-auto">
                <div class="card-header">
                    <h4>Time Register</h4>
                </div>
                <div class="card-body">
                    @include('time-registers._preview')
                </div>
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    @endif
    <!-- JCR Details -->
    <div class="page-landscape" style="page: jcr-landscape;">
        <div class="row mb-4">
            <div class="col-md-12 mx-auto">
                <div class="card">
                    <div class="card-body">
                        @include('jcr._preview_content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Checklist A -->
    @if($groupedChecklists['a'])
    <div class="row mb-4">
        <div class="col-md-11 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2>Checklist A: Pre-Departure Checklist</h2>
                </div>
                <div class="card-body">
                    @include('checklists.partials._preview_content', ['checklist' => $groupedChecklists['a']])
                </div>
                <div class="card-footer">
                    <!-- Signatures Section -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card mt-4 w-100">
                                        <div class="card-body">
                                            @if($groupedChecklists['a']->creatorSignature)
                
                                                <div class="signature mb-3 p-3 border rounded">
                                                    <h5>Creator Signature</h5>
                                                    <div class="d-flex align-items-center">
                                                        <div class="signature-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            {{ substr($groupedChecklists['a']->creatorSignature->user->name, 0, 1) }}
                                                        </div>
                                                        <div class="ms-3">
                                                            <strong>{{ $groupedChecklists['a']->creatorSignature->user->name }}</strong><br>
                                                            {{ $groupedChecklists['a']->creatorSignature->signed_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </div>
                                                    @if($groupedChecklists['a']->creatorSignature->comments)
                                                        <div class="mt-2 text-muted"><strong>Comments:</strong> {{ $groupedChecklists['a']->creatorSignature->comments }}</div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card mt-4 w-100">
                                        <div class="card-body">
                                            @if($groupedChecklists['a']->approverSignature)
                                                <div class="signature mb-3 p-3 border rounded">
                                                    <h5>Approver Signature</h5>
                                                    <div class="d-flex align-items-center">
                                                    <div class="signature-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                        {{ substr($groupedChecklists['a']->approverSignature->user->name, 0, 1) }}
                                                    </div>
                                                    <div class="ms-3">
                                                        <strong>{{ $groupedChecklists['a']->approverSignature->user->name }}</strong><br>
                                                        {{ $groupedChecklists['a']->approverSignature->signed_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Checklist B -->
    @if($groupedChecklists['b'])
    <div class="page-break"></div>
    <div class="row mb-4">
        <div class="col-md-11 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2>Checklist B: On- Site Checklist</h2>
                </div>
                <div class="card-body">
                    @include('checklists.partials._preview_content', ['checklist' => $groupedChecklists['b']])
                </div>
                <div class="card-footer">
                    <!-- Signatures Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mt-4 w-100">
                                <div class="card-body">
                                    @if($groupedChecklists['b']->creatorSignature)
                                        <div class="signature mb-3 p-3 border rounded">
                                            <h5>Creator Signature</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="signature-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($groupedChecklists['b']->creatorSignature->user->name, 0, 1) }}
                                                </div>
                                                <div class="ms-3">
                                                    <strong>{{ $groupedChecklists['b']->creatorSignature->user->name }}</strong><br>
                                                    {{ $groupedChecklists['b']->creatorSignature->signed_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                            @if($groupedChecklists['b']->creatorSignature->comments)
                                                <div class="mt-2 text-muted"><strong>Comments:</strong> {{ $groupedChecklists['b']->creatorSignature->comments }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mt-4 w-100">
                                <div class="card-body">
                                    @if($groupedChecklists['b']->externalSignature)
                                        <div class="signature mb-3 p-3 border rounded">
                                            <h5>Approver Signature</h5>
                                            <div class="d-flex align-items-center">
                                            <div class="signature-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                {{ substr($groupedChecklists['b']->externalSignature->name, 0, 1) }}
                                            </div>
                                            <div class="ms-3">
                                                <strong>{{ $groupedChecklists['b']->externalSignature->name }}</strong><br>
                                                {{ $groupedChecklists['b']->externalSignature->signed_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Checklist C -->
    @if($groupedChecklists['c'])
    <div class="page-break"></div>
    <div class="row mb-4">
        <div class="col-md-11 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h2>Checklist C: Upon-Arrival Checklist</h2>
                </div>
                <div class="card-body">
                    @include('checklists.partials._preview_content', ['checklist' => $groupedChecklists['c']])
                </div>
                <div class="card-footer">
                    <!-- Signatures Section -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mt-4 w-100">
                                <div class="card-body">
                                    @if($groupedChecklists['c']->creatorSignature)  
                                        <div class="signature mb-3 p-3 border rounded">
                                            <h5>Creator Signature</h5>
                                            <div class="d-flex align-items-center">
                                                <div class="signature-avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    {{ substr($groupedChecklists['c']->creatorSignature->user->name, 0, 1) }}
                                                </div>
                                                <div class="ms-3">
                                                    <strong>{{ $groupedChecklists['c']->creatorSignature->user->name }}</strong><br>
                                                    {{ $groupedChecklists['c']->creatorSignature->signed_at->format('d/m/Y H:i') }}
                                                </div>
                                            </div>
                                            @if($groupedChecklists['c']->creatorSignature->comments)
                                                <div class="mt-2 text-muted"><strong>Comments:</strong> {{ $groupedChecklists['c']->creatorSignature->comments }}</div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mt-4 w-100">
                                <div class="card-body">
                                    @if($groupedChecklists['c']->approverSignature)
                                        <div class="signature mb-3 p-3 border rounded">
                                            <h5>Approver Signature</h5>
                                            <div class="d-flex align-items-center">
                                            <div class="signature-avatar bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                {{ substr($groupedChecklists['c']->approverSignature->user->name, 0, 1) }}
                                            </div>
                                            <div class="ms-3">
                                                <strong>{{ $groupedChecklists['c']->approverSignature->user->name }}</strong><br>
                                                {{ $groupedChecklists['c']->approverSignature->signed_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer mt-4 text-center">
        <p>Printed on: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <script src="/static/bootstrap-5.2.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8"
    crossorigin="anonymous"></script>
    <script>
        window.onload = function() {
            // Auto-print if desired
            // window.print();
        }
    </script>
</body>
</html>