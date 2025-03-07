<?php

namespace Shaferllc\Analytics\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Computed;
use Shaferllc\Analytics\Models\Stat;
use Shaferllc\Analytics\Models\Website;
use Shaferllc\Analytics\Traits\ComponentTrait;
use Shaferllc\Analytics\Traits\DateRangeTrait;

class SocialNetworks extends Component
{
    use DateRangeTrait, WithPagination, ComponentTrait;

    #[Locked]
    public Website $website;    public function mount(Website $website)
    {
        $this->website = $website;   
    }

    #[Layout('analytics::layouts.app')]
    public function render()
    {

        $data = $this->query(
            type: 'social_network',
            to: $this->to,
            from: $this->from
        );

        return view('analytics::livewire.social-networks', [
            'data' => $data['data'],
            'first' => $data['first'],
            'last' => $data['last'],
            'total' => $data['total'],
            'page' => 'social_networks',
            'range' => $this->range,
        ])->layoutData([
            'range' => $this->range,
            'page' => 'social_networks',
            'website' => $this->website,
        ]);
    }
}
