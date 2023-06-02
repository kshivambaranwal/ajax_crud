<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="border rounded p-4">
                    <div class="alert alert-danger print-error-msg" style="display:none">
                        <ul></ul>
                    </div>
                    <form action="#" method="POST" id="add_employee_form" enctype="multipart/form-data">
                        @csrf
                        <!-- 2 column grid layout with text inputs for the first and last names -->
                        <div class="row mt-4">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example1">Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="form6Example1" name="name"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example2">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="email" id="form6Example2"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example3">Phone<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="phone" id="form6Example3"
                                        class="form-control" />
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example4">File<span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="file" id="form6Example4"
                                        class="form-control" />
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label" for="form6Example3">Company<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="company"
                                        aria-label="Default select example">
                                        <option selected disabled hidden>Select Company</option>
                                        @isset($company)
                                            @forelse ($company as $cp)
                                                <option value="{{ $cp->id ?? '' }}">{{ $cp->name ?? '' }}</option>
                                            @empty
                                                <option value="">No record Found </option>
                                            @endforelse
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-outline">
                                    <label class="form-label"
                                        for="form6Example4">Education<span class="text-danger">*</span></label>
                                    <select class="form-select"  name="education" aria-label="Default select example">
                                        <option selected disabled hidden>Select Education</option>
                                        @isset($education)
                                            @forelse ($education as $ed)
                                                <option value="{{ $ed->id ?? '' }}">{{ $ed->name ?? '' }}</option>
                                            @empty
                                                <option value="">No record Found </option>
                                            @endforelse
                                        @endisset
                                    </select>

                                </div>
                            </div>
                        </div>
                        <!-- Checkbox -->
                        <div class="form-check d-flex mt-4">
                            <label class="form-check-label">Hobby<span class="text-danger">*</span> </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input me-2" type="checkbox" name="hobby[]"
                                value="C" id="form6Examplec" />
                            <label class="form-check-label" for="form6Examplec"> Cricket </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input me-2" type="checkbox" name="hobby[]"
                                value="S" id="form6Examples" />
                            <label class="form-check-label" for="form6Examples"> Singing </label>
                            &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input me-2" type="checkbox" name="hobby[]"
                                value="T" id="form6Examplet" />
                            <label class="form-check-label" for="form6Examplet"> Traveling </label>
                        </div>
                        <div class="form-check d-flex mt-4">
                            <label class="form-check-label">Gender<span class="text-danger">*</span> </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input me-2" name="gender" type="radio"
                                value="M" id="form6Examplec" />
                            <label class="form-check-label" for="form6Examplec"> Male </label>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <input class="form-check-input me-2" type="radio" name="gender"
                                value="F" id="form6Examples" />
                            <label class="form-check-label" for="form6Examples"> Female </label>
                        </div>
                        <div class="row">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example4">Experience<span
                                        class="text-danger">*</span></label>
                                <div class="d-flex">
                                    <input type="text" name="experience[]" id="form6Example4"
                                        class="form-control" />
                                    &nbsp;<button type="button" class="btn btn-light add"
                                        data-bs-toggle="modal" data-bs-target="#addEmployeeModal"><i
                                            class="bi-plus-circle me-2"></i></button>
                                </div>
                            </div>
                            <div class="add-input">

                            </div>
                        </div>
                        <!-- Message input -->
                        <div class="form-outline mt-4">
                            <label class="form-label" for="form6Example7">Additional information<span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="form6Example7" name="message" rows="4"></textarea>
                        </div>
                        <!-- Submit button -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-block" id="submit">Add
                    User</button>
            </div>
            </form>
        </div>
    </div>
</div>
