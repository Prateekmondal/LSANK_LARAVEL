<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Jcr;

class JobNo extends Component
{
    public $unitno;
    public $jobNo=0;
    public $units;

    public $counter=0;

    public function mount($value)
    {
        $this->units = ['GJ-16-AA-5015', 'GJ-16-AA-5016', 'GJ-16-AA-5017', 'GJ-16-BS-2279', 'GJ-16-AF-9702', 'GJ-16-AF-9723'];
    }

    public function updatedUnitno($value)
    {
        if (!empty($value)) {
            // Fetch job numbers for the selected unit
            // $this->counter++;
            $jobNo = Jcr::where('unitNo', $value)
                            ->get('jobNo')
                            ->last();
            if ($jobNo){
                $this->jobNo = $jobNo->jobNo+1;
            }
            else {
                $this->jobNo = 1;
                // $this->counter--;
            }
            // dd($this->jobNo);
        } else {
            $this->jobNo = 0;
            // $this->counter--;
        }
    }

    public function render()
    {
        return view('livewire.job-no');
    }
}
