<?php
namespace nplesa\Observer\Http\Livewire;
use Livewire\Component;
class JobsTable extends Component {
    public function replay($jobId){}
    public function render(){ return view('observer::livewire.jobs-table'); }
}
