<x-layout title="Posts List">
    <x-intro-card title="Blog Posts 🤨"
                  subtitle="Here's all the blog posts we have come up with!"></x-intro-card>

    <div x-data="postIndexData"
         class="bg-white dark:bg-zinc-800 rounded-lg border border-zinc-200 shadow-md dark:border-zinc-700">
        <header class="px-5 py-2 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex justify-between items-center">
                <h2 class="text-lg text-zinc-800 dark:text-white">All Posts</h2>
                <a href="{{ route('posts.create') }}"
                   class="bg-blue-800 text-white font-bold text-sm uppercase rounded hover:bg-blue-700 flex items-center justify-center px-6 py-2.5">New</a>
            </div>
        </header>
        <div class="">
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <thead
                        class="text-xs font-semibold uppercase text-zinc-700 bg-zinc-100 dark:text-zinc-300 dark:bg-zinc-900">
                    <tr class="font-semibold text-left">
                        <th class="px-2 py-3">S/N</th>
                        <th class="px-2 py-3 whitespace-nowrap">Title</th>
                        <th class="px-2 py-3 whitespace-nowrap">Category</th>
                        <th class="px-2 py-3 whitespace-nowrap">Published At</th>
                        <th class="px-2 py-3 whitespace-nowrap">Published</th>
                        <th class="px-2 py-3 whitespace-nowrap">Action</th>
                    </tr>
                    </thead>
                    <tbody
                        class="text-sm text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach ($posts as $post)
                        <tr class="hover:bg-ash-100">
                            <td class="p-2 pl-4">{{ $loop->iteration + (($posts->currentPage() - 1) * $posts->perPage()) }}
                                .
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex">
                                    <div class="w-12 h-auto flex-shrink-0 mr-2 sm:mr-3">
                                        <img class="rounded-sm"
                                             src="{{ $post->thumbnail }}" alt="">
                                    </div>
                                    <div class="font-medium text-ash-800">{{ $post->title }}</div>
                                </div>
                            </td>
                            <td>{{ $post->category->title }}</td>
                            <td class="p-2 whitespace-nowrap">{{ $post->published_at?->format('F d, Y') }}</td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($post->is_published)
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div> Published
                                    @else
                                        <div class="h-2.5 w-2.5 rounded-full bg-yellow-500 mr-2"></div> Draft
                                    @endif
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <a href="{{ config('app.frontend_url'). 'p/' . $post->slug }}" target="_blank"
                                   class="inline-flex bg-green-600 hover:bg-green-700 text-white px-1.5 py-1.5 mx-1 rounded">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-linecap="round"
                                              stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                                <a href="{{ route('posts.edit', $post->slug) }}"
                                   class="inline-flex bg-blue-800 hover:bg-blue-900 text-white px-1.5 py-1.5 mx-1 rounded">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </a>
                                <button @click="confirmDelete('{{ $post->slug }}', $dispatch)"
                                        class="inline-flex bg-red-500 hover:bg-red-600 text-white px-1.5 py-1.5 mx-1 rounded">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="p-8 w-full">
                    {{ $posts->links() }}
                </div>
            </div>

            <x-modal open="openDeleteModal" title="Confirm Delete">
                <div class="px-4 py-8">Are you sure you want to delete this post?</div>

                <form id="deleteForm" method="POST"
                      class="flex px-4 py-2 border-t border-zinc-300 dark:border-zinc-600 justify-end" action="">
                    @csrf
                    @method('delete')

                    <button type="button" @click="openDeleteModal = false; $dispatch('body-scroll', {})"
                            class="bg-transparent hover:bg-zinc-700 border border-zinc-300 dark:border-zinc-600 text-white font-bold text-sm uppercase rounded  flex items-center justify-center px-6 py-2.5 mr-4">
                        Cancel
                    </button>
                    <button type="submit"
                            class="bg-blue-800 hover:bg-blue-700 text-white font-bold text-sm uppercase rounded  flex items-center justify-center px-6 py-2.5">
                        Yes
                    </button>
                </form>
            </x-modal>
        </div>
    </div>

    <script>
        const postIndexData = {
            openDeleteModal: false,
            confirmDelete(slug, $dispatch) {
                document.querySelector('#deleteForm').action = '/posts/' + slug
                this.openDeleteModal = true
                $dispatch('body-scroll', {})
            }
        }

    </script>
</x-layout>
