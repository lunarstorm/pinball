<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-body">
        <h3 class="card-title text-center">
          Email Verification Required
        </h3>

        <p>
          To verify your identity and gain full access, please click on the link
          inside the verification email that was sent to you.
        </p>

        <div class="text-center">
          <button
            :disabled="form.processing"
            type="button"
            class="btn btn-primary"
            @click.prevent="sendAgain"
          >
            <span v-if="form.processing">Sending...</span>
            <span v-else>Email Me Again</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutBlank from '@/Layouts/LayoutBlank.vue';
import InputText from 'vio/components/form/InputText.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import FormError from 'vio/components/form/FormError.vue';

export default {
    name: 'VerifyEmail',
    components: {
        InputText,
        FormError,
    },
    layout: LayoutBlank,
    setup(props) {
        const form = useForm({});

        return {
            form,
        };
    },
    methods: {
        sendAgain() {
            return this.form.post('/email/verification-notification');
        },
    },
};
</script>

<style scoped>
</style>
