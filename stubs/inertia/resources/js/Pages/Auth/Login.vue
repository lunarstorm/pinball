<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-header text-center">
        {{ $page.props.app.name }}
      </div>
      <div class="card-body">
        <div v-if="$page.props.session.status" class="alert alert-success">
          {{ $page.props.session.status }}
        </div>

        <form @submit.prevent="form.post('/login')">
          <div class="form-group">
            <label>Email</label>
            <InputText
              v-model="form.email"
              :error="form.errors.email"
            />
          </div>

          <div class="form-group">
            <label>Password</label>
            <InputText
              v-model="form.password"
              :error="form.errors.password"
              type="password"
            />
          </div>

          <div class="form-group">
            <div class="checkbox">
              <label>
                <input v-model="form.remember" type="checkbox">
                Remember Me
              </label>
            </div>
          </div>

          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-primary btn-block"
          >
            Log In
          </button>
        </form>

        <div class="mt-3 form-text text-center">
          <inertia-link href="/forgot-password" class="text-muted">
            I forgot my password
          </inertia-link>
        </div>
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
    name: 'Login',
    components: {
        InputText,
        FormError,
    },
    layout: LayoutBlank,
    setup(props) {
        const form = useForm({
            email: '',
            password: '',
            remember: false,
        });

        return {
            form,
        };
    },
};
</script>

<style scoped>
</style>
