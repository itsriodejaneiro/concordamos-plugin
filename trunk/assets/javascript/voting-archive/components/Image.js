export default function Image ({ alt = '', src, ...props }) {
	return (
		<img src={`${concordamos.plugin_url}/assets/images/${src}`} alt={alt} {...props}/>
	)
}
