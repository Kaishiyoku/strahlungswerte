<a href="{{ route($route) }}" class="{{ classNames('dropdown-item', ['dropdown-item-active' => $isActive]) }}">
    {!! $title ?? route($route) !!}
</a>
