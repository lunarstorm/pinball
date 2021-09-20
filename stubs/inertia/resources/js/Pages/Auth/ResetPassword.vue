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
						<input-text
						  label="Email"
						  disabled="disabled"
						  v-model="form.email"
						  :error="form.errors.email"
						></input-text>
					</div>

					<div class="form-group">
						<input-text
						  label="New Password"
						  v-model="form.password"
						  :error="form.errors.password"
						  type="password"
						></input-text>
					</div>

					<div class="form-group">
						<input-text
						  label="Confirm Password"
						  v-model="form.password_confirmation"
						  :error="form.errors.password_confirmation"
						  type="password"
						></input-text>
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
import LayoutBlank from "@/Layouts/LayoutBlank";
import InputText from "vio/components/form/InputText";
import {useForm} from "@inertiajs/inertia-vue3";

export default {
	name: "ResetPassword",
	layout: LayoutBlank,
	props: {
		email: String,
		token: String,
		status: String
	},
	components: {
		InputText
	},
	setup(props) {
		console.log('props', props);
		const form = useForm({
			email: props.email,
			password: '',
			password_confirmation: '',
			token: props.token
		});

		return {
			form
		}
	},
}
</script>

<style scoped>

</style>
