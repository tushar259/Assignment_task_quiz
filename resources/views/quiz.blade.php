<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 content-center" style="width: 700px;">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (Auth::user()->type == "admin")
                        <div>
                            <button onclick="showAllAnswersDiv()" class="allAnswerButton">All Answers</button>
                            <button onclick="showBestAnswersDiv()" class="bestAnswerButton">Best Answers</button>
                        </div>
                        <br>
                        <div id="allAnswersForAdmin">
                            @if($quizInfo)
                                Quiz ({{$quizInfo->closes_at}})
                                <table>
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Answer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listOfSubmissions as $submission)
                                            <tr>
                                                <td>{{ $submission['user_name'] }}</td>
                                                <td>{{ $submission['answer'] }}</td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            @endif
                        </div>
                        <div id="bestAnswersForAdmin" style="display: none;">
                            @if($quizInfo)
                                Quiz ({{$quizInfo->closes_at}}) - Best Answers
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Answer</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($answersCount as $answers)
                                            <tr>
                                                <td>{{ $answers['answer'] }}</td>
                                                <td>{{ $answers['count'] }}</td>
                                            </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            @endif
                        </div>

                    @elseif (Auth::user()->type == "user")
                        @if($quizInfo)
                            <div style="text-align: right;">Closing at: {{$quizInfo->closes_at}}</div>
                            <div class="grid grid-cols-2 gap-4 content-start">
                                
                                
                                <div>
                                    <p>Pic 1</p>
                                    <div class="image-div">
                                        <img id="preview1" src="/{{ $quizInfo->image1 }}" alt="Image Preview 1"/>
                                    </div>
                                </div>
                                <div>
                                    <p>Pic 2</p>
                                    <div class="image-div">
                                        <img id="preview2" src="/{{ $quizInfo->image2 }}" alt="Image Preview 2"/>
                                    </div>
                                </div>
                                                     
                            </div>
                            <b>Submit Your Answers(Max 5 Times)</b>
                            <div>
                                <input type="text" value="{{ Auth::user()->name }}" placeholder="User name" disabled>
                            </div>   
                            <div>

                                <form action="{{ route('add-submission') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{$quizInfo->id}}" name="quizId">
                                    @if($quizSelfSubmissions->isNotEmpty())
                                        <input type="text" placeholder="Enter answer" name="newAnswer" value="{{ $quizSelfSubmissions[0]['answer'] }}" id="newAnswer">
                                    @else
                                        <input type="text" placeholder="Enter answer" name="newAnswer" id="newAnswer">
                                    @endif
                                    <button type="button" id="addAnswerBtn">+</button>

                                    <ul id="submissionList">
                                        @if($quizSelfSubmissions->count() > 1)
                                            @foreach($quizSelfSubmissions->skip(1) as $submission)
                                                <li class="submission-item">
                                                    <input type="text" name="answers[]" value="{{ $submission['answer'] }}" placeholder="Enter answer">
                                                    <button type="button" class="deleteBtn">Delete</button>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                    @if($validTime == "valid")
                                        <br>
                                        <button type="submit" class="submitAnswerButton">Submit Answer</button>
                                    @endif
                                </form>
                            </div>
                        
                        @endif 
                            
                    @endif
                </div>
            </div>
        </div>
        <script>
            document.getElementById('addAnswerBtn').addEventListener('click', function() {
                var newAnswer = document.getElementById('newAnswer').value;
                
                var submissionList = document.getElementById('submissionList');

                var li = document.createElement('li');
                li.classList.add('submission-item');

                var input = document.createElement('input');
                input.type = 'text';
                input.name = 'answers[]';
                input.value = ''; 
                input.placeholder = 'Enter answer';

                var deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.classList.add('deleteBtn');
                deleteBtn.textContent = 'Delete';
                deleteBtn.addEventListener('click', function() {
                    li.remove();
                });

                li.appendChild(input);
                li.appendChild(deleteBtn);
                submissionList.appendChild(li);

                
            });

            document.querySelectorAll('.deleteBtn').forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });

            function showBestAnswersDiv(){
                const allAnswersForAdminDiv = document.getElementById('allAnswersForAdmin');
                const bestAnswersForAdminDiv = document.getElementById('bestAnswersForAdmin');

                allAnswersForAdminDiv.style.display = 'none';
                bestAnswersForAdminDiv.style.display = 'block';
            }

            function showAllAnswersDiv(){
                const allAnswersForAdminDiv = document.getElementById('allAnswersForAdmin');
                const bestAnswersForAdminDiv = document.getElementById('bestAnswersForAdmin');

                allAnswersForAdminDiv.style.display = 'block';
                bestAnswersForAdminDiv.style.display = 'none';    
            }

        </script>
    </div>
</x-app-layout>
