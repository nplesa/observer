<?php
namespace nplesa\Observer\Http\Livewire;
use Livewire\Component;
class RecordedRequestsTable extends Component {
    public function replay($correlationId){}
    public function render(){ return view('observer::livewire.recorded-requests-table'); }
}
