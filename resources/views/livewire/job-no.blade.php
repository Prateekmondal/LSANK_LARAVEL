<div>
    <div id='div_id_unitNo' class='mb-3'>
        <label for='id_unitNo' class='form-label requiredField'>
            Logging Unit Number<span class='asteriskField'>*</span>
        </label>
        <select name='unitNo' class='select form-select' id='id_unitNo' wire:model.change="unitno">
            <option value='' disabled {{ old('unitNo') ? '' : 'selected' }}>--- Select Unit ---</option>
            @foreach ($units as $unitNo)
                <option value='{{ $unitNo }}' {{ old('unitNo') == $unitNo ? 'selected' : '' }}>
                    {{ $unitNo }}
                </option>
            @endforeach
        </select>
        @error('unitNo')
            <small class='error'>{{ $message }}</small>
        @enderror
    </div>
    <div id='div_id_jobDate' class='mb-3'>
        <label for='id_jobDate' class='form-label requiredField'>
            Job Date<span class='asteriskField'>*</span>
        </label>
        <input type='text' name='jobDate' placeholder='DD-MM-YYYY' data-mask='00-00-0000' class='dateinput form-control'
            id='id_jobDate' autocomplete='off' maxlength='10' value='{{ old('jobDate') }}'>
        @error('jobDate')
            <small class='error'>{{ $message }}</small>
        @enderror
    </div>

    <div class="mb-4">
        <label for="jobNo" class="block text-sm font-medium text-gray-700">Job Number</label>
        <input wire:model="jobNo" id="jobNo"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            value="{{ $jobNo }}" disabled>
    </div>
    <div id='div_id_jobNo' class='mb-3'>
        <label for='id_jobNo' class='form-label requiredField'>
            Job No.<span class='asteriskField'>*</span>
        </label>
        <input type='text' name='jobNo' placeholder='Job No.' class='textinput textInput form-control' wire:model="jobNo" id='id_jobNo'
            value='{{ old('jobNo') ? old('jobNo') : $jobNo }}'>
        @error('jobNo')
            <small class='error'>{{ $message }}</small>
        @enderror
    </div>
</div>