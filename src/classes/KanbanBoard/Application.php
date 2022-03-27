<?php

namespace KanbanBoard;

use Michelf\Markdown;

class Application
{

	public function __construct($github, $repositories, $paused_labels = array())
	{
		$this->github = $github;
		$this->repositories = $repositories;
		$this->paused_labels = $paused_labels;
	}

	public function board()
	{
		$ms = array();
		foreach ($this->repositories as $repository) {
			foreach ($this->github->milestones($repository) as $data) {
				$ms[$data['title']] = $data;
				$ms[$data['title']]['repository'] = $repository;
			}
		}
		ksort($ms);
		$milestones = [];
		foreach ($ms as $name => $data) {
			$issues = $this->issues($data['repository'], $data['number']);
			$percent = self::_percent($data['closed_issues'], $data['open_issues']);
			if ($percent) {
				$milestones[] = array(
					'milestone' => $name,
					'url' => isset($data['html_url']) ? $data['html_url'] : null,
					'progress' => $percent,
					'queued' => isset($issues['queued']) ? $issues['queued'] : null,
					'active' => isset($issues['active']) ? $issues['active'] : null,
					'completed' => isset($issues['completed']) ? $issues['completed'] : null,
				);
			}
		}
		return $milestones;
	}

	private function issues($repository, $milestone_id)
	{
		$getedIssues = $this->github->issues($repository, $milestone_id);

		foreach ($getedIssues as $ii) {
			if (isset($ii['pull_request']))
				continue;
			$issues[$ii['state'] === 'closed' ? 'completed' : (($ii['assignee']) ? 'active' : 'queued')][] = array(
				'id' => $ii['id'], 'number' => $ii['number'],
				'title'            	=> $ii['title'],
				'body'             	=> Markdown::defaultTransform($ii['body']),
				'url' => $ii['html_url'],
				'assignee'         	=> (is_array($ii) && array_key_exists('assignee', $ii) && !empty($ii['assignee'])) ? $ii['assignee']['avatar_url'] . '?s=16' : NULL,
				'paused'			=> self::labels_match($ii, $this->paused_labels),
				'progress'			=> self::_percent(
					substr_count(strtolower($ii['body']), '[x]'),
					substr_count(strtolower($ii['body']), '[ ]')
				),
				'closed'			=> $ii['closed_at']
			);
		}

		if (isset($issues['active'])) {
			usort($issues['active'], function ($a, $b) {
				return count($a['paused']) - count($b['paused']) === 0 ? strcmp($a['title'], $b['title']) : count($a['paused']) - count($b['paused']);
			});
			return $issues;
		} else if (isset($issues))
			return $issues;
		else
			return null;
	}

	private static function _state($issue)
	{
		if ($issue['state'] === 'closed')
			return 'completed';
		else if (Utilities::hasValue($issue, 'assignee') && count($issue['assignee']) > 0)
			return 'active';
		else
			return 'queued';
	}

	private static function labels_match($issue, $needles)
	{
		if (Utilities::hasValue($issue, 'labels')) {
			foreach ($issue['labels'] as $label) {
				if (in_array($label['name'], $needles)) {
					return array($label['name']);
				}
			}
		}
		return array();
	}

	private static function _percent($complete, $remaining)
	{
		$total = $complete + $remaining;
		if ($total > 0) {
			$percent = ($complete or $remaining) ? round($complete / $total * 100) : 0;
			return array(
				'total' => $total,
				'complete' => $complete,
				'remaining' => $remaining,
				'percent' => $percent
			);
		}
		return array();
	}
}
