@extends('employee.layout')

@section('content')
    <div class="card" style="max-width: 500px; margin: 100px auto;">
        <div class="card-body text-center">
            <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" class="mb-3"
     style="width: 180px; height: 180px; object-fit: cover;border-radius: 50%;">
            <ul class="list-group list-group-flush text-start">
                <li class="list-group-item"><strong>နာမည်:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>အီးမေးလ်:</strong> {{ $user->email }}</li>
                <li class="list-group-item"><strong>မွေးသက္ကရာဇ်:</strong> {{ $user->dob->format('d M, Y') }}</li>
                <li class="list-group-item"><strong>လက်ရှိနေရပ်လိပ်စာ:</strong> {{ $user->currentaddress }}</li>
                <li class="list-group-item"><strong>ဖုန်းနံပါတ်:</strong> {{ $user->phno }}</li>
                <li class="list-group-item"><strong>ဌာန:</strong> {{ $user->department }}</li>
                <li class="list-group-item"><strong>လက်ထပ်မှုအခြေအနေ:</strong>
                    {{ $user->getMarriedStatusName() }}</li>
                <li class="list-group-item"><strong>ကျား/မ:</strong> {{ $user->getGenderName() }}</li>
                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                    data-bs-target="#imageUploadModal">
                    ပုံပြောင်းမည်
                </button>
            </ul>

        </div>

        <!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('employee.profile.image.update') }}" method="POST" enctype="multipart/form-data" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header">
        <h5 class="modal-title" id="imageUploadModalLabel">ပုံအသစ် Upload ပြုလုပ်ရန်</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ပိတ်ရန်"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="image" class="form-label">ပုံရွေးပါ</label>
          <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ဖျက်ရန်</button>
        <button type="submit" class="btn btn-success">Upload</button>
      </div>
    </form>
  </div>
</div>

    </div>
@endsection
