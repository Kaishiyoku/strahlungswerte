<a
    class="{{ classNames('navbar-link', ['navbar-link-active' => $isActive]) }}"
    data-provide-dropdown="true"
    data-dropdown-target="#{{ $id }}"
>
    {!! $title !!}

    {!! getHeroiconSvgImageHtml('s-chevron-down') !!}
</a>
<div id="{{ $id }}" class="dropdown flex flex-col hidden rounded-md shadow-xl">
    @foreach ($entries as $entry)
        {!! $entry->render() !!}
    @endforeach
</div>
