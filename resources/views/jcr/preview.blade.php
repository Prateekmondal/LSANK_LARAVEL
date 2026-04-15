@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card w-100">
        <div class="card-header bg-primary text-white">
            <h3 class="m-0">Please review all details before final submission</h3>
        </div>
        <div class="card-body">
            @include('jcr._preview_content')

            {{-- Creator can assign a party chief and submit final JCR in one action --}}
            @if(auth()->check())
                <div class="mb-4">
                    <form action="{{ route('jcr.submit', $jcr->id) }}" method="POST" class="form-inline">
                        @csrf
                        @if(empty($jcr->party_chief_id))
                            <div class="form-group mr-2">
                                <label for="party_chief_id" class="mr-2">Forward to Party Chief :</label>
                                <select name="party_chief_id" id="party_chief_id" class="form-control mb-1" required>
                                    <option value="" disabled selected>-- Select Party Chief --</option>
                                    @foreach($partyChiefs as $pc)
                                        <option value="{{ $pc->id }}">{{ ucwords(strtolower($pc->name)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <input type="hidden" name="party_chief_id" value="{{ $jcr->party_chief_id }}">
                            <div class="alert alert-info">
                                Assigned Party Chief: {{ optional($jcr->partyChief)->name ?? 'N/A' }}
                            </div>
                        @endif

                        <button type="submit" class="btn btn-success btn-lg mx-2 mt-2">
                            <i class="fas fa-check-circle"></i> Submit Final JCR
                        </button>

                        <a href="{{ route('jcr.edit', $jcr->id) }}" class="btn btn-warning btn-lg mx-2 mt-2">
                            <i class="fas fa-edit"></i> Edit JCR
                        </a>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection