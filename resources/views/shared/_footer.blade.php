<footer class="px-4 sm:px-0 pt-12 pb-4">
    <div class="text-muted">
        {{ getYearsFrom(env('APP_YEAR_CREATED')) }},

        {{ env('APP_AUTHOR') }},

        v{{ env('APP_VERSION_NUMBER') }}
    </div>
</footer>
