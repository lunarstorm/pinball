<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-header text-center">
        Password Reset Request
      </div>
      <div class="card-body">
        <div v-if="$page.props.session.status" class="alert alert-success">
          {{ $page.props.session.status }}
        </div>

        <form @submit.prevent="form.post('/forgot-password')">
          <div class="form-group">
            <label>Email</label>
            <InputText
              v-model="form.email"
              :error="form.errors.email"
            />
          </div>

          <button
            :disabled="form.processing"
            type="submit"
            class="btn btn-success btn-block"
          >
            Submit Request
          </button>
        </form>

        <div class="mt-3 form-text text-center">
          <inertia-link href="/login">
            Go Back to Login
          </inertia-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import LayoutBlank from '@/Layouts/LayoutBlank.vue';
import InputText from 'vio/components/form/InputText.vue';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: 'RequestPasswordReset',
    components: {
        InputText,
    },
    layout: LayoutBlank,
    props: {
        status: String,
    },
    setup(props) {
    //console.log(props);

        const form = useForm({
            email: '',
        });

        return {
            form,
        };
    },
};
</script>

<style scoped>
</style>
