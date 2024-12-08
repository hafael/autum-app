<template>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="px-4 flex justify-between items-center">
            <h3 class="text-md uppercase text-gray-400">Notificações</h3>
            <a href="#" @click="closeNotificationCentral" class="text-sm font-bold uppercase text-indigo-500 hover:underline">Fechar</a>
        </div>

        <p class="text-gray-700 dark:text-gray-400 px-4" v-show="!hasUnreadNotications">Nenhuma notificação nova.</p>

        <div class="mt-3 space-y-1 divide-y divide-gray-200">
            <div class="flex px-4 py-2 cursor-pointer bg-white dark:bg-gray-900" 
                v-for="notification in filteredNotifications" 
                :key="notification.id"
                @click="openNotification(notification)">
                <span class="relative w-3 mr-3">
                    <span class="w-3 h-3 top-2 absolute">
                        <span class="w-full h-full rounded-full bg-red-100 dark:bg-red-200 animate-ping absolute top-0 left-0" v-if="!notification.read_at"></span>
                        <span class="w-3 h-3 rounded-full absolute top-0 left-0" 
                            :class="{
                                'bg-red-200 dark:bg-red-300': !notification.read_at,
                                'bg-blue-200 dark:bg-blue-300': notification.read_at
                             }"></span>
                    </span>
                </span>
                <div class="flex justify-between w-full">
                    <div>
                        <h3 class="text-md font-semibold">{{ notification.data.subject }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ notification.data.greeting }}</p>
                    </div>
                    <div class="text-right w-20">
                        <p class="text-xs text-gray-400">{{ $filters.fromDateTime(notification.created_at, 'd/m/Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $filters.fromDateTime(notification.created_at, 'HH:mm') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between mx-4 mb-2 mt-3" v-if="hasNotifications">
            <a href="#" @click="viewAll = true" class="text-sm font-bold text-indigo-500 hover:underline" v-show="showViewAllButton">Ver mais</a>
            <a href="#" @click="clearAllNotifications" class="text-sm font-bold text-red-500 hover:underline">Limpar</a>
        </div>

        <!-- Show Notification Modal -->
        <DialogModal :show="showingNotificationModal" @close="closeNotification">
            <template #title>
                Notificação
            </template>

            <template #content>
                <h3 class="text-2xl font-bold mb-3">{{ reading.data.subject }}</h3>
                <h4 class="text-xl font-semibold text-gray-500 dark:text-gray-200 mb-3">{{ reading.data.greeting }}</h4>
                <div class="block relative my-2 text-gray-800 dark:text-gray-300" v-html="reading.data.body"></div>
                <p class="text-gray-500 text-sm">{{ $filters.fromDateTime(reading.created_at) }}</p>
            </template>

            <template #footer>
                <SecondaryButton @click="closeNotification" class="mr-2">
                    Fechar
                </SecondaryButton>
                <PrimaryButton @click="callToAction(reading.data.action_url)">
                    {{ reading.data.action_text }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </div>
</template>

<script>

    import DialogModal from '@/Components/DialogModal.vue'
    import PrimaryButton from '@/Components/PrimaryButton.vue'
    import SecondaryButton from '@/Components/SecondaryButton.vue'
    
    export default {

        components: {
            DialogModal,
            PrimaryButton,
            SecondaryButton
        },

        props: {
            notifications: {
                type: Object,
                default: {
                    data: [],
                    meta: {}
                }
            }
        },

        computed: {
            hasNotifications() {
                return !_.isEmpty(this.notifications.data);
            },

            hasUnreadNotications() {
                return !_.isEmpty(_.filter(this.notifications.data, (n) => {
                    return _.isEmpty(n.read_at);
                }));
            },

            showViewAllButton() {
                return _.size(this.filteredNotifications) <= 5;
            },

            filteredNotifications() {
                if(!this.viewAll) {
                    return _.slice(this.notifications.data, 0, 5);
                }
                return this.notifications.data;
            }
        },

        data() {
            return {
                reading: {},
                showingNotificationModal: false,
                viewAll: false
            }
        },

        methods: {
            openNotification(notification) {
                this.reading = Object.assign({}, notification);
                this.showingNotificationModal = true;
                setTimeout(() => {
                    if(this.showingNotificationModal) {
                        this.markAsRead();
                    }
                }, 1300);
            },

            closeNotification() {
                this.showingNotificationModal = false;
                this.reading = {};
            },
            
            markAsRead() {
                let self = this;

                axios.get(route('api.notifications.read', self.reading.id)).then((response) => {
                    console.log('markAsRead response', response);
                    self.$emit('update', response.data);
                }).catch((error) => {
                    console.log('markAsRead error', error);
                });
            },

            callToAction(url) {
                this.closeNotification();
                if(!_.isEmpty(url)) {
                    setTimeout(() => {
                        window.location.href = url;
                    }, 1500);
                }
            },

            closeNotificationCentral() {
                this.viewAll = false;
                this.$emit('close');
            },

            clearAllNotifications() {
                let self = this;

                axios.delete(route('api.notifications.clear')).then((response) => {
                    console.log('clearAllNotifications response', response);
                    self.$emit('update');
                }).catch((error) => {
                    console.log('clearAllNotifications error', error);
                });
            }
        }
    }
</script>