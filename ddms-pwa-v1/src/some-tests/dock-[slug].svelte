<script context="module">

	export async function load({ params, url }) {

		const page = {
			title: params.slug,
			pageUrl: url,
			slug: params.slug,
			// pathName: url.pathname,
			body: 'lorem ipsum'
		};
		return {
			props: {
				page
			}
		};
	}
</script>

<script>
    import Dock from "../Dock.svelte";

	export let post;
</script>

<!-- how to read [slug] param??? -->

<h1>Dock details. {post.title}</h1>
<p>Page slug: {post.slug}</p>
<p>Page url: {post.pageUrl}</p>

<Dock pathName={post.slug}/>
