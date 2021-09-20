<template>
  <div>
    <div class="card mx-auto" style="max-width: 400px">
      <div class="card-body">
        <h3 class="card-title text-center">Email Verification Required</h3>

        <p>
          To verify your identity and gain full access, please click on the link
          inside the verification email that was sent to you.
        </p>

        <div class="text-center">
          <button
            @click.prevent="sendAgain"
            :disabled="form.processing"
            type="button"
            class="btn btn-primary"
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
import LayoutBlank from "@/Layouts/LayoutBlank";
import InputText from "vio/components/form/InputText";
import { useForm } from "@inertiajs/inertia-vue3";
import FormError from "vio/components/form/FormError";

export default {
  name: "VerifyEmail",
  layout: LayoutBlank,
  components: {
    InputText,
    FormError,
  },
  setup(props) {
    const form = useForm({});

    return {
      form,
    };
  },
  methods: {
    sendAgain() {
      return this.form.post("/email/verification-notification");
    },
  },
};
</script>

<style scoped>
</style>
