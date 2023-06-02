
                    <!-- 2 column grid layout with text inputs for the first and last names -->
                    <div class="row mt-4">
                        <div class="col">
                            <div class="form-outline">
                                <input type="hidden" name="user_id" value="{{ $empdata->id ?? '' }}">
                                <label class="form-label" for="name">Name<span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name" value="{{ $empdata->name ?? '' }}"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="email">Email<span class="text-danger">*</span></label>
                                <input type="email" name="email" value="{{ $empdata->email ?? '' }}" id="email"
                                    class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="phone">Phone<span class="text-danger">*</span></label>
                                <input type="number" name="phone" value="{{ $empdata->phone ?? '' }}" id="phone"
                                    class="form-control" />
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example4">File<span
                                        class="text-danger">*</span></label>
                                <input type="file" name="file" id="form6Example4" class="form-control" />
                                <img src="{{ asset($empdata->file ?? '') }}" width="100px" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example3">Company<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="company" aria-label="Default select example">
                                    <option selected disabled hidden>Select Company</option>
                                    @isset($company)
                                        @forelse ($company as $cp)
                                            <option value="{{ $cp->id ?? '' }}" @selected($empdata->company == $cp->id)>
                                                {{ $cp->name ?? '' }}</option>
                                        @empty
                                            <option value="">No record Found </option>
                                        @endforelse
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-outline">
                                <label class="form-label" for="form6Example4">Education<span
                                        class="text-danger">*</span></label>
                                <select class="form-select" name="education" aria-label="Default select example">
                                    <option selected disabled hidden>Select Education</option>
                                    @isset($education)
                                        @forelse ($education as $ed)
                                            <option value="{{ $ed->id ?? '' }}" @selected($empdata->education == $ed->id)>
                                                {{ $ed->name ?? '' }}</option>
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
                        <input class="form-check-input me-2" type="checkbox" name="hobby[]" value="C"
                            id="form6Examplec" @checked(in_array('C', json_decode($empdata->hobby))) />
                        <label class="form-check-label" for="form6Examplec"> Cricket </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-check-input me-2" @checked(in_array('S', json_decode($empdata->hobby))) type="checkbox" name="hobby[]"
                            value="S" id="form6Examples" />
                        <label class="form-check-label" for="form6Examples"> Singing </label>
                        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-check-input me-2" type="checkbox" @checked(in_array('T', json_decode($empdata->hobby)))
                            name="hobby[]" value="T" id="form6Examplet" />
                        <label class="form-check-label" for="form6Examplet"> Traveling </label>
                    </div>
                    <div class="form-check d-flex mt-4">
                        <label class="form-check-label">Gender<span class="text-danger">*</span> </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-check-input me-2" name="gender" type="radio"
                            @checked($empdata->gender == 'M') value="M" id="form6Examplec" />
                        <label class="form-check-label" for="form6Examplec"> Male </label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input class="form-check-input me-2" type="radio" name="gender"
                            @checked($empdata->gender == 'F') value="F" id="form6Examples" />
                        <label class="form-check-label" for="form6Examples"> Female </label>
                    </div>
                    <div class="row">
                        <div class="form-outline">
                            <label class="form-label" for="form6Example4">Experience<span
                                    class="text-danger">*</span></label>
                            <div class="d-flex">
                                <button type="button" class="btn btn-light add" data-bs-toggle="modal"
                                    data-bs-target="#addEmployeeModal"><i class="bi-plus-circle me-2"></i></button>
                            </div>
                        </div>
                        <div class="add-input">
                            @foreach (json_decode($empdata->experience) as $emp)
                                <div class="d-flex parent mt-2">
                                    <input type="text" name="experience[]" value="{{ $emp ?? '' }}"
                                        id="form6Example4" class="form-control" />
                                    &nbsp;<button type="button" class="btn btn-light remove" data-bs-toggle="modal"
                                        data-bs-target="#addEmployeeModal"><i
                                            class="bi-dash-circle me-2"></i></button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Message input -->
                    <div class="form-outline mt-4">
                        <label class="form-label" for="form6Example7">Additional information<span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="form6Example7" name="message" rows="4">{{ $empdata->message ?? '' }}</textarea>
                    </div>
                    <!-- Submit button -->
            
