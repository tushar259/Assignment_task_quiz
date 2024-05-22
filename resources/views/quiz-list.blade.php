<x-app-layout>
    

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 content-center" style="width: 700px;">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if (Auth::user()->type == "admin")
                        
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Submissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listOfQuiz as $quiz)
                                    <tr>
                                        <td>{{ $quiz->closes_at }}</td>
                                        <td>{{ $quiz->submissions_count }}</td>
                                        <!-- <td>0</td> -->
                                        <td><a href="{{ route('quiz-submission-info', ['id' => $quiz->id]) }}"><button class="view-button">View Answers</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table>

                    @elseif (Auth::user()->type == "user")
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Submissions</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listOfQuiz as $quiz)
                                    <tr>
                                        <td>{{ $quiz->closes_at }}</td>
                                        <td>{{ $quiz->submissions_count }}</td>
                                        <!-- <td>0</td> -->
                                        <td><a href="{{ route('quiz-info-to-answer', ['id' => $quiz->id]) }}"><button class="view-button">Answer Quiz</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
