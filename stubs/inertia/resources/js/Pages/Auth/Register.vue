<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-header text-center">New User Registration</div>
      <div class="card-body">
        <div v-if="$page.props.session.status" class="alert alert-success">
          {{ $page.props.session.status }}
        </div>

        <form @submit.prevent="submit">
          <div class="form-group">
            <label>Your Full Name</label>
            <input-text
              v-model="form.name"
              :error="form.errors.name"
            ></input-text>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input-text
              v-model="form.email"
              :error="form.errors.email"
            ></input-text>
          </div>

          <div class="form-group">
            <label>Choose a Password</label>
            <input-text
              v-model="form.password"
              :error="form.errors.password"
              type="password"
            ></input-text>
          </div>

          <div class="form-group">
            <label>Confirm Password</label>
            <input-text
              v-model="form.password_confirmation"
              :error="form.errors.password_confirmation"
              type="password"
            ></input-text>
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
import LayoutBlank from "@/Layouts/LayoutBlank";
import InputText from "vio/components/form/InputText";
import { useForm } from "@inertiajs/inertia-vue3";
import FormError from "vio/components/form/FormError";

export default {
  name: "Register",
  layout: LayoutBlank,
  components: {
    InputText,
    FormError,
  },
  setup(props) {
    const form = useForm({
      name: "",
      email: "",
      password: "",
      password_confirmation: "",
    });

    return {
      form,
    };
  },
  methods: {
    submit() {
      return this.form.post("/register", this.form);
    },
  },
};
</script>

<style scoped>
</style>
