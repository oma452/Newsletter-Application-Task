@component('mail::message')
# 🇪🇬 Daily Egypt News

@foreach ($news as $item)
## {{ $item->title }}

{{ $item->description }}

[Read More]({{ $item->url }})

---

@endforeach

Thanks,<br>
{{ config('app.name') }}
@endcomponent
