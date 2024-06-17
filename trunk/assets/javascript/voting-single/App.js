import Panel from './pages/Panel'
import Single from './pages/Single'

export function App({ initialData }) {
	return initialData.is_panel ? (
		<Panel initialData={initialData} />
	) : (
		<Single initialData={initialData} />
	)
}
