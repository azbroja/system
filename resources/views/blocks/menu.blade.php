@foreach ($items as $route => $item)
    <a href="{{ route($route, $item['args']) }}" class="{{ 'menu-item-'.$route }}{{ isset($active) && $active === $route ? ' active' : '' }}">{{ $item['label'] }}</a>
@endforeach
