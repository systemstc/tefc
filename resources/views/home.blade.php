@extends('layouts.app')

@section('title', 'Home — TEFC')

@section('content')
    @foreach($page->sections as $section)
        @if($section->is_active ?? true)
            @include('cms.sections.' . $section->type, [
                'title' => $section->title,
                'data' => $section->content
            ])
        @endif
    @endforeach
@endsection

@section('scripts')
<script>
    // Global Scroll Trigger for Charts
    document.addEventListener('DOMContentLoaded', function () {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const bars = entry.target.querySelectorAll('.bar-fill');
                    bars.forEach(bar => {
                        bar.style.width = bar.getAttribute('data-width') || bar.style.width;
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.2 });

        const chartSection = document.querySelector('.impact-bottom');
        if (chartSection) observer.observe(chartSection);
    });
</script>
@endsection
