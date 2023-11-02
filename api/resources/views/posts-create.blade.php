<x-layout title="Create New Post">
    <form id="create-post-form" x-data="createPostData" enctype="multipart/form-data"
          action="{{ route('posts.store') }}" method="POST"
          class="relative bg-white h-full overflow-hidden dark:bg-zinc-800 rounded-lg border border-zinc-200 shadow-md dark:border-zinc-700">
        @csrf

        <header
            class="sticky top-0 z-10 bg-white dark:bg-zinc-800 px-5 py-2 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex justify-between items-center">
                <div>
                    <h2 x-show="editing === false"
                        class="form-input block w-full text-sm text-black bg-transparent border-transparent dark:text-zinc-300"
                        @click="editing = true" x-html="title"></h2>
                    <div x-show="editing === true" class="text-base flex items-center">
                        <label for="title" class="hidden"></label>
                        <input type="text" placeholder="Post Title" id="title" x-model="title" name="title"
                               x-on:blur="editing = false" required value="{{ old('title') }}"
                               class="form-input min-w-[20rem] block w-full rounded-md text-sm text-black border-zinc-300 dark:text-zinc-300 dark:border-zinc-600 dark:bg-zinc-800 focus:border-blue-400 focus:outline-none focus:ring-blue-600"/>
                        <button type="button" class="text-zinc-800 dark:text-zinc-300 hover:text-blue-700"
                                @click="editing = false">
                            <svg class="ml-2 w-6 h-6 " fill="none" stroke="currentColor"
                                 stroke-width="1.5" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex">
                    <button type="button" @click="saveDraft"
                            class="border border-blue-800 text-blue-700 font-bold text-sm uppercase rounded-l-md hover:bg-blue-800/30 flex items-center justify-center px-6 py-2.5">
                        Save As Draft
                    </button>
                    <button type="button" @click="publishPost"
                            class="bg-blue-800 text-white font-bold text-sm uppercase rounded-r-md hover:bg-blue-700 flex items-center justify-center px-6 py-2.5">
                        Publish
                    </button>
                </div>
            </div>
        </header>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="overflow-auto h-full pt-4 pb-16">
            <div class="grid grid-cols-12 gap-6 text-zinc-700">
                <div class="col-span-6 px-5 py-2">

                    <div class="text-sm py-2">
                        <label class="text-zinc-700 dark:text-zinc-400" for="description">Meta Description</label>
                        <div
                            class="relative text-zinc-500 focus-within:text-blue-800 dark:focus-within:text-blue-500">
                            <textarea rows="4" name="description" id="description" required
                                      class="form-input relative block w-full rounded-md mt-1 text-sm text-black border-zinc-300 dark:text-zinc-300 dark:border-zinc-600
                                       @error('description') border-red-500 dark:border-red-400 @enderror  dark:bg-zinc-800 focus:border-blue-400 focus:outline-none focus:ring-blue-600"
                            >{{old('description')}}</textarea>
                        </div>
                        @error('description')
                        <div class="text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-sm py-2">
                        <label class="text-zinc-700 dark:text-zinc-400" for="category">Category</label>
                        <div
                            class="relative text-zinc-500 focus-within:text-blue-800 dark:focus-within:text-blue-500">
                            <select id="category" name="category_id" required
                                    class="form-select pl-10 block w-full rounded-md mt-1 text-sm dark:text-zinc-300  border-zinc-300 dark:border-zinc-600 dark:bg-zinc-800  focus:border-blue-400 focus:outline-none focus:ring-blue-600">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($category->id == old('category_id'))>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-width="1.5"
                                          d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125"
                                          stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </div>
                        @error('category_id')
                        <div class="text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-sm py-2">
                        <label class="text-zinc-700 dark:text-zinc-400" for="tags">Tags</label>
                        <x-tag-input name="tags" :suggestions="$tags->pluck('name')"/>
                        @error('tags')
                        <div class="text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="col-span-6 px-5 py-2">

                    <div class="py-2">
                        <label class="text-zinc-700 dark:text-zinc-400" for="thumbnail">Banner</label>
                        <div class="mx-auto">
                            <div class="flex items-center justify-center w-full mx-auto pb-[56.25%] relative mt-2">
                                <div class="absolute inset-0 overflow-hidden">
                                    <label for="thumbnail"
                                           class="flex flex-col items-center justify-center w-full h-full border-2 border-ash-500 rounded border-dashed hover:bg-ash-200 hover:border-ash-600">
                                        <img id="preview" class="absolute object-cover w-full" alt="" src="">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="w-12 h-12 text-gray-400 group-hover:text-gray-600"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <p
                                            class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-gray-600">
                                            Select a photo</p>
                                    </label>
                                    <input type="file" class="hidden" id="thumbnail" name="thumbnail" required
                                           accept="image/*" @change="showPreview(event)"/>
                                </div>
                            </div>
                        </div>
                        @error('banner')
                        <div class="text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="col-span-12 px-5 py-2">

                    <div class="py-2">
                        <label class="text-zinc-700 dark:text-zinc-400" for="post-body">Content</label>
                        <textarea rows="10" name="body" id="post-body"
                                  class="form-input" required
                        >{{old('body')}}</textarea>
                        @error('body')
                        <div class="text-sm text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="hidden">
                    <label>
                        <input type="checkbox" name="shouldPublish">
                    </label>
                </div>
            </div>
        </div>
    </form>

    @push('stylesheets')
        <link rel="stylesheet" href="{{asset('css/easymde.min.css')}}">
        <script src="{{asset('js/easymde.min.js')}}"></script>
    @endpush

    @push('scripts')
        <script>
            const easyMDE = new EasyMDE({
                element: document.querySelector('#post-body'),
                previewClass: ['px-6', 'py-4', 'prose', 'dark:prose-invert', 'max-w-full', 'text-zinc-700', 'dark:text-zinc-300', 'dark:bg-zinc-900'],
                minHeight: "300px",
                toolbar: [
                    'bold', 'italic', 'strikethrough', '|',
                    'heading-1', 'heading-2', 'heading-3', '|',
                    'quote', 'unordered-list', 'ordered-list', '|',
                    'link', 'image', 'table', '|',
                    'preview', 'side-by-side', 'fullscreen', '|',
                    'guide'
                ],
                uploadImage: true,
                imageUploadFunction: uploadImage,
                imagePathAbsolute: true,
            });

            easyMDE.codemirror.on("change", () => {
                document.querySelector('#post-body').textContent = easyMDE.value();
            });

            async function uploadImage (file, onSuccess, onError) {
                const formData = new FormData();
                formData.append('file', file);
                try {
                    const response = await fetch('/temporary-upload', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{csrf_token()}}',
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        return Promise.reject(Error('Image upload failed'));
                    }

                    const data = await response.json();
                    onSuccess(data.url)
                    return data.url
                } catch (error) {
                    onError(error);
                }
            }
        </script>
        <script>
            const createPostData = {
                editing: false,
                title: 'New Post Title',
                showPreview(event) {
                    if (event.target.files.length > 0) {
                        const src = URL.createObjectURL(event.target.files[0]);
                        const preview = document.getElementById("preview");
                        preview.src = src;
                        preview.style.display = "block";
                    }
                },
                saveDraft() {
                    document.querySelector('input[name="shouldPublish"]').checked = false;
                    document.querySelector('#create-post-form').submit();
                },
                publishPost() {
                    document.querySelector('input[name="shouldPublish"]').checked = true;
                    document.querySelector('#create-post-form').submit();
                }
            }
        </script>
    @endpush


</x-layout>
