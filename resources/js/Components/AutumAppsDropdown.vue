<template>
    <jet-dropdown :align="align" width="60" >
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center p-2 text-gray-600 focus:text-gray-800 dark:focus:text-gray-300 hover:text-gray-800 dark:hover:text-gray-300 active:text-gray-800 dark:active:text-gray-300 transition">
                    <Squares2X2Icon class="w-6 h-6" />
                </button>
            </span>
        </template>

        <template #content>
            <div class="w-60">
                
                <!-- Platform links -->
                <div class="block px-4 py-2 text-xs text-gray-700 dark:text-gray-300 uppercase">
                    Atalhos
                </div>

                <div>
                    <ul class="grid grid-cols-1 gap-2 my-2 mx-4">
                        <li>
                            <a :href="appsDashboardUrl" class="flex items-center space-x-2 hover:underline text-gray-800 dark:text-gray-200 hover:text-indigo-700">
                                <SparklesIcon class="w-5 h-5" /> <span class="text-sm font-bold">Diretório de Apps</span>
                            </a>
                        </li>
                        <li>
                            <a :href="accountUrl" class="flex items-center space-x-2 hover:underline text-gray-800 dark:text-gray-200 hover:text-indigo-700">
                                <UserCircleIcon class="w-5 h-5" /> <span class="text-sm font-bold">Conta</span>
                            </a>
                        </li>
                        <li>
                            <a :href="billingUrl" class="flex items-center space-x-2 hover:underline text-gray-800 dark:text-gray-200 hover:text-indigo-700">
                                <CreditCardIcon class="w-5 h-5" /> <span class="text-sm font-bold">Faturamento</span>
                            </a>
                        </li>
                        <li>
                            <a :href="supportUrl" class="flex items-center space-x-2 hover:underline text-gray-800 dark:text-gray-200 hover:text-indigo-700">
                                <LifebuoyIcon class="w-5 h-5" /> <span class="text-sm font-bold">Ajuda</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Apps dropdown -->
                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-300 uppercase">
                    Meus apps
                </div>

                <div>

                    <ul class="grid grid-cols-3 gap-4 mx-4 pb-2">
                        <li v-for="app in apps.data"
                            :key="app.id">
                            <a :href="app.login_url" 
                                target="_blank"
                                class="flex flex-col text-gray-700 dark:text-gray-300 font-semibold text-xs text-center"
                                :title="app.name">
                                <img class="w-full h-auto rounded-md mb-1 shadow" :src="app.icon_image_url" :alt="`${app.acronym} - ${app.name}`">
                                <span>{{ app.name }}</span>
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        </template>
    </jet-dropdown>
</template>

<script>

    import JetDropdown from '@/Components/Dropdown.vue'
    import JetDropdownLink from '@/Components/DropdownLink.vue'
    import { Squares2X2Icon, UserCircleIcon, SparklesIcon, CreditCardIcon, LifebuoyIcon } from '@heroicons/vue/24/outline'

    export default {
        components: {
            JetDropdown,
            JetDropdownLink,
            Squares2X2Icon,
            UserCircleIcon,
            SparklesIcon,
            CreditCardIcon,
            LifebuoyIcon,
        },

        props: {
            align: {
                type: String,
                default: () => {
                    return 'left';
                }
            },
            appsRoute: {
                type: String,
                default: () => {
                    return route('api.applications.index');
                }
            },
            platformBaseUrl: {
                type: String,
                default: () => {
                    return 'https://accounts-local.autum.com.br';
                }
            },
        },

        data() {
            return {
                apps: { data: [], current: {}},
            }
        },

        computed: {
            currentApp() {
                return this.apps.current;
            },

            changeTeamNameRoute() {
                let env = 'accounts-local.';
                return `https://${env}autum.com.br/teams/${this.currentTeam.id}`
            },

            createTeamsRoute() {
                let env = 'accounts-local.';
                return `https://${env}autum.com.br/teams/create`
            },
            
            appsDashboardUrl() {
                return this.platformBaseUrl + '/dashboard';
            },
            accountUrl() {
                return this.platformBaseUrl + '/user/profile';
            },
            billingUrl() {
                return this.platformBaseUrl + '/user/subscriptions';
            },
            supportUrl() {
                return this.platformBaseUrl + '/ajuda';
            },
            getApplicationsUrl() {
                return this.appsRoute;
            },
        },

        mounted() {
            this.fetchApps();
        },

        methods: {
            
            fetchApps() {
                let self = this;
                axios.get(this.getApplicationsUrl).then((response) => {
                    self.apps = response.data;
                }).catch((error) => {
                    console.log('fetchTeams error', error);
                });
            },
        }
    }
</script>
