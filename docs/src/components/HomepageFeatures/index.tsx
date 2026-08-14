import type {ReactNode} from 'react';
import clsx from 'clsx';
import Heading from '@theme/Heading';
import styles from './styles.module.css';

type FeatureItem = {
  title: string;
  description: ReactNode;
};

const FeatureList: FeatureItem[] = [
  {
    title: 'Blade directives',
    description: (
      <>
        Drop <code>@pwaHead</code> and <code>@pwaSw</code> into your layout to
        render the manifest link, theme-color meta tag, and service worker
        registration script.
      </>
    ),
  },
  {
    title: 'Smart caching',
    description: (
      <>
        Network-first for navigation, cache-first for static assets, with
        Inertia and Livewire requests bypassed to avoid stale responses.
      </>
    ),
  },
  {
    title: 'One Artisan command',
    description: (
      <>
        <code>php artisan pwa:generate</code> writes your{' '}
        <code>manifest.json</code> and publishes the <code>sw.js</code> stub
        from a single config file.
      </>
    ),
  },
];

function Feature({title, description}: FeatureItem) {
  return (
    <div className={clsx('col col--4')}>
      <div className="text--center padding-horiz--md">
        <Heading as="h3">{title}</Heading>
        <p>{description}</p>
      </div>
    </div>
  );
}

export default function HomepageFeatures(): ReactNode {
  return (
    <section className={styles.features}>
      <div className="container">
        <div className="row">
          {FeatureList.map((props, idx) => (
            <Feature key={idx} {...props} />
          ))}
        </div>
      </div>
    </section>
  );
}
