<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StatBadge extends Component
{
    /**
     * Label displayed under/next to the value (e.g. "Followers").
     *
     * @var string
     */
    public string $label;

    /**
     * Numeric or text value to display (e.g. 123, "12k").
     *
     * @var mixed
     */
    public $value;

    /**
     * Optional icon name or HTML to display.
     *
     * @var string|null
     */
    public ?string $icon;

    /**
     * Optional link URL to wrap the badge.
     *
     * @var string|null
     */
    public ?string $link;

    /**
     * Create the component instance.
     *
     * @param string $label
     * @param mixed $value
     * @param string|null $icon
     * @param string|null $link
     */
    public function __construct(string $label, $value, ?string $icon = null, ?string $link = null)
    {
        $this->label = $label;
        $this->value = $value;
        $this->icon = $icon;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.stat-badge');
    }
}

