<?php
namespace nplesa\Observer\Http\Livewire;
use Livewire\Component;
class RequestsTable extends Component {
    protected $listeners = ['newRequest' => 'refresh'];
    public function refresh() { $this->emitSelf('refresh'); }
    public function render() { return view('observer::livewire.requests-table'); }
}
