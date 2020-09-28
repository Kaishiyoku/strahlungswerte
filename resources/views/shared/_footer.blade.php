<footer class="pt-12 pb-4 text-gray-500">
    <div class="text-muted">
        {{ getYearsFrom(env('APP_YEAR_CREATED')) }},

        {{ env('APP_AUTHOR') }},

        v{{ env('APP_VERSION_NUMBER') }}
    </div>
</footer>
