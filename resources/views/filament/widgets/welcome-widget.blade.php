<x-filament::section>
    <div class="flex items-center gap-x-3">
        <div class="flex-shrink-0">
            <x-filament::icon
                alias="panels::widgets.welcome.icon"
                icon="heroicon-o-sparkles"
                class="h-6 w-6 text-primary-500"
            />
        </div>
        <div>
            <h2 class="text-xl font-semibold tracking-tight">
                Welcome to World of Pets Admin
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Manage your pet breeds, assessments, and view website analytics.
            </p>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('filament.admin.resources.pets.index') }}" class="relative flex flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex items-center gap-x-3">
                <div class="flex flex-shrink-0 items-center justify-center rounded-lg bg-primary-50 p-1 dark:bg-primary-500/10">
                    <x-filament::icon
                        alias="panels::widgets.welcome.actions.manage-pets"
                        icon="heroicon-m-star"
                        class="h-5 w-5 text-primary-500"
                    />
                </div>

                <div>
                    <h3 class="font-medium">
                        Manage Pet Breeds
                    </h3>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Add, edit, or delete dog and cat breeds
                    </p>
                </div>
            </div>
        </a>

        <a href="{{ route('filament.admin.resources.assessments.index') }}" class="relative flex flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex items-center gap-x-3">
                <div class="flex flex-shrink-0 items-center justify-center rounded-lg bg-primary-50 p-1 dark:bg-primary-500/10">
                    <x-filament::icon
                        alias="panels::widgets.welcome.actions.view-assessments"
                        icon="heroicon-m-clipboard-document-check"
                        class="h-5 w-5 text-primary-500"
                    />
                </div>

                <div>
                    <h3 class="font-medium">
                        View Assessments
                    </h3>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Browse personality assessment data
                    </p>
                </div>
            </div>
        </a>

        <a href="/" target="_blank" class="relative flex flex-col rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
            <div class="flex items-center gap-x-3">
                <div class="flex flex-shrink-0 items-center justify-center rounded-lg bg-primary-50 p-1 dark:bg-primary-500/10">
                    <x-filament::icon
                        alias="panels::widgets.welcome.actions.visit-site"
                        icon="heroicon-m-globe-alt"
                        class="h-5 w-5 text-primary-500"
                    />
                </div>

                <div>
                    <h3 class="font-medium">
                        Visit Website
                    </h3>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Preview your website as visitors see it
                    </p>
                </div>
            </div>
        </a>
    </div>
</x-filament::section>