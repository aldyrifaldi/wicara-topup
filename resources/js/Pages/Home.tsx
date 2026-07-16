import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'

export default function IndexPage() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
      <div className="container mx-auto px-4 py-16">
        <div className="text-center mb-16">
          <h1 className="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Wicara Topup
          </h1>
          <p className="text-xl text-gray-600 dark:text-gray-300">
            Modern Digital Products & Services Platform
          </p>
        </div>

        <div className="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
          <Card>
            <CardHeader>
              <CardTitle>Digital Products</CardTitle>
              <CardDescription>
                Game credits, mobile top-ups, and more
              </CardDescription>
            </CardHeader>
            <CardContent>
              <Button className="w-full">Explore Products</Button>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Gaming Services</CardTitle>
              <CardDescription>
                Professional boosting and gaming services
              </CardDescription>
            </CardHeader>
            <CardContent>
              <Button className="w-full">View Services</Button>
            </CardContent>
          </Card>

          <Card>
            <CardHeader>
              <CardTitle>Rewards & Points</CardTitle>
              <CardDescription>
                Earn rewards and climb the leaderboards
              </CardDescription>
            </CardHeader>
            <CardContent>
              <Button className="w-full">Join Now</Button>
            </CardContent>
          </Card>
        </div>

        <div className="mt-16 text-center">
          <p className="text-gray-600 dark:text-gray-400 mb-4">
            Built with Laravel 11, React 18, TypeScript, and shadcn/ui
          </p>
          <div className="flex justify-center gap-4">
            <Button variant="outline">Documentation</Button>
            <Button variant="outline">API Reference</Button>
          </div>
        </div>
      </div>
    </div>
  )
}
