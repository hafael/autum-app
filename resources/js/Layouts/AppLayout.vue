<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
//import { route } from 'vendor/tightenco/ziggy/src/js';


import ToggleDarkMode from '@/Components/ToggleDarkMode.vue';
import NotificationIcon from '@/Icons/NotificationIcon.vue';
import NotificationCentral from '@/Components/NotificationCentral.vue';
import TeamsDropdown from '@/Components/TeamsDropdown.vue';
import AutumAppsDropdown from '@/Components/AutumAppsDropdown.vue';
import { CogIcon, UserCircleIcon, ArrowRightStartOnRectangleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);
const showingNotificationDropdown = ref(false);
const notifications = ref({data: []});
const notificationPooling = ref(null);

const hasUnreadNotifications = computed(() => {
    return notifications.value.data.filter((notification) => {
        return ! notification.read_at;
    }).length > 0;
});

const teamAlertVisible = () => {
    return localStorage.getItem('teamAlert') && localStorage.getItem('teamAlert') !== 'dismiss';
};

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const gotToMyAccount = () => {
    router.push(route('profile.show'));
};

const logout = () => {
    router.post(route('logout'));
};

const fetchNotifications = () => {
    axios.get(route('api.notifications.index')).then(response => {
        notifications.value = response.data;
    });
};

const startNotificationPooling = () => {
    notificationPooling.value = setInterval(() => {
        fetchNotifications();
    }, 10000);
};

const dismissTeamAlert = () => {
    localStorage.setItem('teamAlert', 'dismiss');
};

const routeIs = (name) => {
    return route().current(name) || String(route().current()).startsWith(name);
};

const profileIs = (profile) => {
    return profile === 'admin' && props.$page.auth.user.is_admin ? true : false;
};

const toggleNotifications = () => {
    showingNotificationDropdown.value = ! showingNotificationDropdown.value;
};

onMounted(() => {
    startNotificationPooling();
});

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center gap-4">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-9 w-auto" name="Autum" acronym="HD" />
                                </Link>
                                <autum-apps-dropdown class="ml-2" />
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">

                            <ToggleDarkMode />

                            <NotificationIcon 
                                :unread-notifications="hasUnreadNotifications"
                                @toggle="toggleNotifications" />

                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <TeamsDropdown v-if="$page.props.jetstream.hasTeamFeatures" />
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="size-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.name }}

                                                <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            <template #icon><UserCircleIcon class="w-4 h-4" /></template>
                                            Profile
                                        </DropdownLink>
                                        

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            <template #icon><CogIcon class="w-4 h-4" /></template>
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-gray-200 dark:border-gray-600" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                <template #icon><ArrowRightStartOnRectangleIcon class="w-4 h-4" /></template>
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">

                            <ToggleDarkMode />

                            <NotificationIcon 
                                :unread-notifications="hasUnreadNotifications"
                                @toggle="toggleNotifications" />

                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
                                <img class="size-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                <template #icon><UserCircleIcon class="w-4 h-4" /></template>
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                <template #icon><CogIcon class="w-4 h-4" /></template>
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    <template #icon><ArrowRightStartOnRectangleIcon class="w-4 h-4" /></template>
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200 dark:border-gray-600" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </ResponsiveNavLink>

                                <!-- Team Switcher -->
                                <template v-if="$page.props.auth.user.all_teams.length > 1">
                                    <div class="border-t border-gray-200 dark:border-gray-600" />

                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        Switch Teams
                                    </div>

                                    <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                        <form @submit.prevent="switchToTeam(team)">
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg v-if="team.id == $page.props.auth.user.current_team_id" class="me-2 size-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Notification Central -->
                <div :class="{'block': showingNotificationDropdown, 'hidden': ! showingNotificationDropdown}" class="relative md:fixed md:w-full h-full bottom-0">
                    <div class="hidden md:block absolute w-full h-full bg-white dark:bg-black opacity-75" @click="toggleNotifications"></div>
                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 md:absolute md:top-0 md:right-0 md:w-1/4 md:h-full md:border-l border-gray-400 dark:border-gray-500 bg-white dark:bg-black">
                        <NotificationCentral
                            class="pt-4 pb-1 w-full md:h-full"
                            :notifications="notifications"
                            @update="fetchNotifications"
                            @close="toggleNotifications" />
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Team Alert -->
            <div class="bg-blue-700 dark:bg-blue-700 dark:text-white shadow" v-if="$page.props.auth.user.id !== $page.props.auth.user.current_team_owner && teamAlertVisible">
                <div class="flex justify-between max-w-7xl mx-auto px-4 py-1.5 md:px-6 lg:px-8">
                    <span class="text-sm">Mostrando resultados para a equipe <span class="font-semibold">{{ $page.props.auth.user.current_team_name }}</span> em <span class="font-semibold">{{ $page.props.user.current_team_organization }}</span>.</span>
                    <a href="#" @click.prevent="dismissTeamAlert"><XMarkIcon class="w-5 h-5" /></a>
                </div>
            </div>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <footer class="border-t-1 border-indigo-400">
                <div class="max-w-7xl mx-auto pt-6 pb-3 px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-sm text-gray-400">Autum @ 2024 | <a href="https://autum.com.br" class="text-indigo-400 text-bold" title="Autum">autum.com.br</a></div>
                </div>
                <div class="flex justify-center">
                    <div class="autum-brand light inline-block dark:hidden"></div>
                    <div class="autum-brand dark hidden dark:inline-block"></div>
                </div>
                
            </footer>

        </div>
    </div>
</template>
