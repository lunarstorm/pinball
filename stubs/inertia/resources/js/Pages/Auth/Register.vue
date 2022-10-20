<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-header text-center">
        New User Registration
      </div>
      <div class="card-body">
        <div v-if="$page.props.session.status" class="alert alert-success">
          {{ $page.props.session.status }}
        </div>

        <form @submit.prevent="submit">
          <div class="form-group">
            <label>Your Full Name</label>
            <InputText
              v-model="form.name"
              :error="form.errors.name"
            />
          </div>

          <div class="form-group">
            <label>Email</label>
            <InputText
              v-model="form.email"
              :error="form.errors.email"
            />
          </div>

          <div class="form-group">
            <label>Choose a Password</label>
            <InputText
              v-model="form.password"
              :error="form.errors.password"
              type="password"
            />
          </div>

          <div class="form-group">
            <label>Confirm Password</label>
            <InputText
              v-model="form.password_confirmation"
              :error="form.errors.password_confirmation"
              type="password"
            />
          </div>

          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-primary btn-block"
          >
            Create My Account
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutBlank from '@/Layouts/LayoutBlank';
import InputText from 'vio/components/form/InputText';
import { useForm } from '@inertiajs/inertia-vue3';
import FormError from 'vio/components/form/FormError';

export default {
    name: 'Register',
    components: {
        InputText,
        FormError,
    },
    layout: LayoutBlank,
    setup(props) {
        const form = useForm({
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        });

        return {
            form,
        };
    },
    methods: {
        submit() {
            return this.form.post('/register', this.form);
        },
    },
};
</script>

<style scoped>
</style>
