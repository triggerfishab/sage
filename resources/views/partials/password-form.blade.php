<section class="component-post-password">
  <form
    action="{{ site_url('wp-login.php?action=postpass', 'login_post') }}"
    method="post"
    class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-10 col-lg-7 col-xl-6">
        <h1>{{ __( 'Log in' ) }}</h1>

        <p class="mb-3">{{ __('This content is only available for visitors with a password. Please enter your password below.', 'sage' ) }}</p>

        <div class="mb-3">
          <label class="sr-only" for="field-{{ $label }}">{{ __( 'Password' ) }}</label>
          <input
            id="field-{{ $label }}"
            type="password"
            placeholder="{{ __( 'Password' ) }}"
            name="post_password"
            tabindex="99">
        </div>

        <button
          tabindex="100"
          type="submit"
          name="Submit"
          class="btn btn-primary">{{ __( 'Log in' ) }}</button>
      </div>
    </div>
  </form>
</section>
