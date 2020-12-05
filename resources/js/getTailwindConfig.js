import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '../../tailwind.config';

const getTailwindConfig = () => resolveConfig(tailwindConfig);

export default getTailwindConfig;
