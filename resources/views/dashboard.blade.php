<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 content-center" style="width: 700px;">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- {{ __("You're logged in!") }} -->
                    
                    @if (Auth::user()->type == "admin")
                        <form action="{{ route('upload-quiz') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                            <div class="grid grid-cols-2 gap-4 content-start">
                                <div>
                                    
                                    <div class="image-div">
                                        <img id="preview1" src="/images/preview_image/500x500.jpg" alt="Image Preview 1"/>
                                    </div>
                                </div>
                                <div>
                                    
                                    <div class="image-div">
                                        <img id="preview2" src="/images/preview_image/500x500.jpg" alt="Image Preview 2"/>
                                        <!-- <img id="preview2" src="https://via.placeholder.com/500" alt="Image Preview 2" style="max-width: 100%; max-height: 100%;" /> -->
                                    </div>
                                </div>
                                <div>
                                    
                                    <input type="file" id="image1" name="image1" accept="image/*" onchange="previewImage(1)"/>
                                    <button type="button" class="custom-file-upload" onclick="document.getElementById('image1').click();">Choose Image 1</button>
                                </div>
                                <div>
                                    
                                    <input type="file" id="image2" name="image2" accept="image/*" onchange="previewImage(2)"/>
                                    <button type="button" class="custom-file-upload" onclick="document.getElementById('image2').click();">Choose Image 2</button>
                                </div>                        
                            </div>
                            <br>
                            <div>
                                <label for="time">Set Time:</label>
                                <select name="time" id="time" required>
                                    <option value="1 min">1 min</option>
                                    <option value="5 min">5 min</option>
                                    <option value="10 min">10 min</option>
                                    <option value="20 min">20 min</option>
                                    <option value="30 min">30 min</option>
                                    <option value="1 Hour">1 Hour</option>
                                    <option value="1 Day">1 Day</option>
                                    <option value="2 Days">2 Days</option>
                                </select>
                            </div>
                            <br>
                            <div>
                                <button type="submit" class="adminUploadQuiz">UPOAD QUIZ</button>
                                <!-- <input type="submit" value="UPOAD QUIZ" name="UPOAD QUIZ"> -->
                            </div>
                            <div>
                                <p id="successMessage">{{ session('success') }}</p>
                            </div>
                        </form>


                    @elseif (Auth::user()->type == "user")
                        <script>
                            window.location.href = "{{ route('quiz-list') }}";
                        </script>
                    @endif



                </div>
            </div>
        </div>
    </div>
    <script>
        function previewImage(index) {
            const file = document.getElementById(`image${index}`).files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(`preview${index}`);
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
