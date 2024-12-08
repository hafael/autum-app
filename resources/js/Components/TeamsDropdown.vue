<template>
    <Dropdown align="right" width="60" >
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                    {{ currentTeam.name }}

                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </span>
        </template>

        <template #content>
            <div class="w-60" v-if="currentTeam.id">
                
                <!-- Team Management -->
                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500">
                    Gerenciar equipe
                </div>

                <!-- Team Settings -->
                <DropdownLink as="a" :href="changeTeamNameRoute" target="_blank">
                    Configurações
                </DropdownLink>

                <DropdownLink as="a" :href="createTeamsRoute" target="_blank">
                    Nova equipe
                </DropdownLink>

                <div class="border-t border-gray-100 dark:border-gray-500"></div>

                <!-- Team Switcher -->
                <div class="block px-4 py-2 text-xs text-gray-400 dark:text-gray-500">
                    Alternar entre equipes
                </div>

                <template v-for="team in teams.data" :key="team.id">
                    <form @submit.prevent="switchToTeam(team)">
                        <DropdownLink as="button">
                            <div class="flex items-center">
                                <svg v-if="team.id == currentTeam.id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>{{ team.name }}</div>
                            </div>
                        </DropdownLink>
                    </form>
                </template>
                
            </div>
        </template>
    </Dropdown>
</template>

<script>

    import Dropdown from '@/Components/Dropdown.vue'
    import DropdownLink from '@/Components/DropdownLink.vue'

    export default {
        components: {
            Dropdown,
            DropdownLink
        },

        data() {
            return {
                teams: { data: [], current: {}},
            }
        },

        computed: {
            currentTeam() {
                return this.teams.current;
            },

            changeTeamNameRoute() {
                let env = 'accounts-local.';
                return `https://${env}autum.com.br/teams/${this.currentTeam.id}`
            },

            createTeamsRoute() {
                let env = 'accounts-local.';
                return `https://${env}autum.com.br/teams/create`
            }
        },

        mounted() {
            this.fetchTeams();
        },

        methods: {
            switchToTeam(team) {
               
                let self = this;
                axios.put(route('api.account.current-team.update'), {
                    team_id: team.id
                })
                .then((response) => {
                    self.$toast.show('Alternando...', {
                            type: 'info', 
                            position: 'top-right',
                        });
                    localStorage.setItem('teamAlert', 'visible');
                    window.location.reload();
                }).catch((error) => {
                    console.log('fetchTeams error', error);
                });
            },

            fetchTeams() {
                let self = this;
                axios.get(route('api.teams.index')).then((response) => {
                    self.teams = response.data;
                }).catch((error) => {
                    console.log('fetchTeams error', error);
                });
            },
        }
    }
</script>
