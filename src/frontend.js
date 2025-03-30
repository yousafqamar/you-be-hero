/**
 * External dependencies
 */
import { registerCheckoutBlock } from '@woocommerce/blocks-checkout';
/**
 * Internal dependencies
 */
import { Block } from './block';
import metadata from './block.json';

registerCheckoutBlock( {
	metadata,
	component: Block,
} );
// In your block's edit.js or frontend.js
const { dispatch } = wp.data;

// Make GET request
const fetchYouBeHeroData = async () => {
    try {
        const response = await dispatch('wc/store/api').get('wc/store/youbehero', {
            param: 'optional_value' // Add parameters if needed
        });
        
        console.log('YouBeHero Response:', response);
        return response;
    } catch (error) {
        console.error('YouBeHero Error:', error);
        throw error;
    }
};

// Example usage in a component
const MyCheckoutComponent = () => {
    const [heroData, setHeroData] = useState(null);
    
    useEffect(() => {
        fetchYouBeHeroData().then(data => setHeroData(data));
    }, []);
    
    return (
        <div>
            {heroData ? (
                <p>{heroData.message} (at {heroData.timestamp})</p>
            ) : (
                <p>Loading YouBeHero data...</p>
            )}
        </div>
    );
};