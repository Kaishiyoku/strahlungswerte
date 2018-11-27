<footer class="z-p-t-50 z-p-b-20">
    <div class="text-muted">
        {{ getYearsFrom(env('APP_YEAR_CREATED')) }},

        {{ env('APP_AUTHOR') }},

        v{{ env('APP_VERSION_NUMBER') }}
    </div>
</footer>