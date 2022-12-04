@props([
    'displayCompanyInfo' => 'true'
])

<section class="h-full gradient-form md:h-screen">
    <div class="container py-12 px-6">
        <div class="flex justify-center items-center flex-wrap h-full g-6 text-gray-800">
            <div class="xl:w-10/12">
                <div class="block bg-white shadow-lg rounded-lg">
                    <div class="lg:flex lg:flex-wrap g-0">
                        <div class="@if($displayCompanyInfo == 'true') lg:w-6/12 @else lg:w-full @endif px-4 md:px-0">
                            <div class="md:p-12 md:mx-6">
                                <div class="text-center">
                                    <img class="mx-auto w-48" src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/lotus.webp" alt="logo" />
                                    <h4 class="text-xl font-semibold mt-1 mb-12 pb-1">WR Company Default</h4>
                                </div>
                                {{ $slot }}
                            </div>
                        </div>
                        @if($displayCompanyInfo == 'true')
                        <div class="lg:w-6/12 flex items-center lg:rounded-r-lg rounded-b-lg lg:rounded-bl-none" style="background: linear-gradient(to right, #ee7724, #d8363a, #dd3675, #b44593);">
                            <div class="text-white px-4 py-6 md:p-12 md:mx-6">
                                <h4 class="text-xl font-semibold mb-6">We are more than just a company</h4>
                                <p class="text-sm">
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                                    consequat.
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
