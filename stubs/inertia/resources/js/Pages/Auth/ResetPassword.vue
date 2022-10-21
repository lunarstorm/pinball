<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px;">
      <div class="card-header text-center">
        Reset Password
      </div>
      <div class="card-body">
        <div v-if="$page.props.session.status" class="alert alert-success">
          {{ $page.props.session.status }}
        </div>

        <form @submit.prevent="form.post('/reset-password')">
          <div class="form-group">
            <InputText
              v-model="form.email"
              label="Email"
              disabled="disabled"
              :error="form.errors.email"
            />
          </div>

          <div class="form-group">
            <InputText
              v-model="form.password"
              label="New Password"
              :error="form.errors.password"
              type="password"
            />
          </div>

          <div class="form-group">
            <InputText
              v-model="form.password_confirmation"
              label="Confirm Password"
              :error="form.errors.password_confirmation"
              type="password"
            />
          </div>

          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-success btn-block"
          >
            Reset My Password
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutBlank from '@/Layouts/LayoutBlank.vue';
import InputText from 'vio/components/form/InputText.vue';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: 'ResetPassword',
    components: {
        InputText,
    },
    layout: LayoutBlank,
    props: {
        email: String,
        token: String,
        status: String,
    },
    setup(props) {
        console.log('props', props);
        const form = useForm({
            email: props.email,
            password: '',
            password_confirmation: '',
            token: props.token,
        });

        return {
            form,
        };
    },
};
</script>

<style scoped>

</style>
